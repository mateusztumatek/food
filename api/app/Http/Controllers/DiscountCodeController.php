<?php

namespace App\Http\Controllers;

use App\Cart;
use App\DiscountCode;
use App\Notification;
use App\Place;
use App\Sale;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;

class DiscountCodeController extends Controller
{
    public function apply(Request $request){
        $request->validate([
            'code' => 'required',
            'sale_id' => 'required|exists:sales,id'
        ]);
        $cart = Cart::getCartBySaleId($request->sale_id, $request);
        try{
            $cart->applyCode($request->code);
            $cart->refresh();
            $cart->save($request);
        }catch(\Exception $e){
            return response()->json(['errors' => ['coupon' => [$e->getMessage()]]], 400);
        }
        return response()->json($cart);
    }
    public function remove(Request $request){
        $request->validate([
            'sale_id' => 'required|exists:sales,id'
        ]);
        $cart = Cart::getCartBySaleId($request->sale_id, $request);
        try{
            $cart->removeCode();
            $cart->refresh();
            $cart->save($request);
        }catch(\Exception $e){
            return response()->json(['errors' => ['coupon' => [$e->getMessage()]]], 400);
        }
        return response()->json($cart);
    }
    public function index(Request $request){
        $user = $request->user('api');
        $places = $user->places;
        $sellouts = Sale::where(function ($q)use($places){
            foreach ($places as $p){
                $q->orWhere('place_id', $p->id);
            }
        })->get();
        $discounts = DiscountCode::where(function ($q)use ($places){
            foreach($places as $p){
                $q->orWhere('place_id', $p->id);
            }
        })->orWhere(function ($q)use($sellouts){
            foreach ($sellouts as $s){
                $q->orWhere('sellout_id', $s->id);
            }
        })->with('sellout', 'place')->filter();
        $discounts = $discounts->where(function ($q)use($request){
            $q->where('code', 'LIKE', '%'.$request->term.'%');
        });
        $discounts = $discounts->paginate(($request->itemsPerPage)? $request->itemsPerPage : 10);
        return response()->json($discounts);
    }
    public function store(Request $request){
        $request->validate([
            'max_uses' => 'required|min:1',
        ]);
        if(!$request->percentage && !$request->value) return response()->json(['errors' => ['percentage' => ['Musisz podać wartość procentową lub liczbową'], 'value' => ['Musisz podać wartość procentową lub liczbową']]], 400);
        if(!$request->sellout_id && !$request->place_id) return response()->json(['errors' => ['sellout_id' => ['Musisz wybrać miejsce lub sprzedaż'], 'place_id' => ['Musisz wybrać miejsce lub sprzedaż']]], 400);
        if($request->auto){
            $request->validate([
                'quantity' => 'required|min:1'
            ]);
        }
        if($request->quantity){
            for($i=0; $i<=$request->quantity; $i++){
                $request->request->set('code', mb_strtoupper(Str::random(6)));
                DiscountCode::create($request->all());
            }
            $code = null;
        }else{
            $request->validate([
                'code' => 'required'
            ]);
            $code = DiscountCode::create($request->all());
        }
        return response()->json($code);
    }
    public function update(Request $request, $id){
        if(!($code = DiscountCode::find($id))) return response()->json(['errors' => 'Nie ma takiego kodu'], 400);
        $request->validate([
            'code' => 'required',
            'max_uses' => 'required|min:1',
        ]);
        if(!$request->percentage && !$request->value) return response()->json(['errors' => ['percentage' => ['Musisz podać wartość procentową lub liczbową'], 'value' => ['Musisz podać wartość procentową lub liczbową']]], 400);
        if(!$request->sellout_id && !$request->place_id) return response()->json(['errors' => ['sellout_id' => ['Musisz wybrać miejsce lub sprzedaż'], 'place_id' => ['Musisz wybrać miejsce lub sprzedaż']]], 400);
        if($request->place_id){
            $request->validate([
                'place_id' => 'exists:places,id'
            ]);
            $place = Place::find($request->place_id);
            if($request->user('api')->id != $place->user_id) return response()->json(['errors' => 'Nie masz uprawnień'], 403);
        }
        if($request->sellout_id){
            $request->validate([
                'sellout_id' => 'exists:sales,id'
            ]);
            $sale = Sale::find($request->sellout_id);
            if($request->user('api')->id != $sale->place->user_id) return response()->json(['errors' => 'Nie masz uprawnień'], 403);
        }
        $code = $code->update($request->all());
        return response()->json($code);
    }

    public function destroy(Request $request, $id = null){
        if(!$id && !is_array($request->all())) return response()->json(['message' => 'Nie ma elementów do usunięcia'], 400);
        foreach ($request->all() as $code){
            $code = DiscountCode::find($code['id']);
            if($code){
                if($code->place_id){
                    if(!$code->place) return response()->json(['errors' => 'Nie ma miejsca'], 400);
                    if($request->user('api')->id != $code->place->user_id) throw new UnauthorizedException();
                }
                if($code->sellout_id){
                    if(!$code->sale) return response()->json(['errors' => 'Nie ma miejsca'], 400);
                    if($request->user('api')->id != $code->sale->place->user_id) throw new UnauthorizedException();
                }
            }else{
                return response()->json(['message' => 'Nie znaleziono kodu'], 400);
            }
            $code->delete();
        }
        return response()->json(true);
    }
    public function pdfs(Request $request){
        $codes = DiscountCode::take(9)->get();
        $codes = $codes->chunk(3);
        PDF::setOptions(['dpi' => 150, 'debugLayoutPaddingBox' => true, 'defaultFont' => 'Arial']);
/*        return view('pdfs.coupons')->with(['chunk' => $codes]);*/
        $pdf = PDF::loadView('pdfs.coupons', ['chunk' => $codes]);
        return $pdf->download('pdf.coupons');
    }
    public function sendToClient(Request $request){
        $request->validate([
            'code_id' => 'required|exists:discount_codes,id',
            'user_id' => 'exists:users,id',
        ]);
        $code = DiscountCode::find($request->code_id);
        if($request->user('api')->id == $code->place->user_id){
            ($code->place)? $text = trans('my.Sklep').' '.$code->place->name : $text = trans('my.Sklep').' '.$code->sellout->place->name;
            $text = $text.' '.trans('my.wysłał Ci kod rabatowy');
            if($code->percentage){
                $text = $text.' '.trans('my.na').' '.$code->percentage.'%';
            }else{
                $text = $text.' '.$code->value.'zł';
            }
            Notification::create([
                'type' => 'discount_code',
                'user_id' => ($request->user_id)? $request->user_id : null,
                'session_key' => $request->session_key,
                'data' => json_encode(['text' => $text])
            ]);
        }
    }
}
