<?php

namespace App\Http\Controllers;

use App\Category;
use App\Events\SaleChange;
use App\Item;
use App\Place;
use App\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function attempt(Request $request, $id){
        $sale = Sale::find($id);
        if(!$sale) return response()->json(['message' => 'Nie ma takiej sprzedaży'], 400);
        if($request->user('api')->id != $sale->place->user_id) return response()->json(['message' => 'Nie masz pozwolenia'], 403);
        $sale->update([
            'last_attempt' => Carbon::now()->addSecond(2)
        ]);
/*        event(new \App\Events\SaleChangeEvent($sale));*/
        /* SEND BROADCAST */
    }
    public function manage(Request $request, $id){
        $sale = Sale::find($id);
        if(!$sale) return response()->json(['message' => 'Nie ma takiej sprzedaży'], 400);
        if(Auth::id() != $sale->place->user_id) return response()->json(['message' => 'Nie masz pozwolenia'], 403);
        $sale->update([
            'last_attempt' => Carbon::now()->addSecond(2)
        ]);
        return response()->json($sale->load('items'));
    }
    public function index(Request $request){
        $sales = Sale::filter()->bounds();
        if($request->user_id){
            $id = $request->user_id;
            $sales = $sales->whereHas('place', function ($q)use($id){
               $q->where('user_id', $id);
            });

        }
        if($request->paginate){
            $sales = $sales->paginate(($request->per_page)? $request->per_page : 10);
        }else{
            $sales = $sales->get();
        }
        return response()->json($sales);
    }
    public function show(Request $request, $id){
        $sale = Sale::with('place')->find($id);
        if(!$sale) return response()->json(['message' => 'Sprzedaż nie znaleziona'], 400);
        $sale->setRelation('items', $sale->items()->paginate(10));
        $sale->categories();
        return response()->json($sale);
    }

    public function categoryItems(Request $request, $sale, $category){
        $sale = Sale::find($sale);
        $category = Category::with('place')->find($category);
        if(!$sale) return response()->json(['message' => 'Nie ma takiej sprzedaży'], 400);
        if(!$category) return response()->json(['message' => 'Nie ma takiej kategorii'], 400);
        $items = $category->items;
        foreach ($items as $key => $item){
            if(!DB::table('sale_items')->where('sale_id', $sale->id)->where('item_id', $item->id)->first()) $items->forget($key);
        }
        return response()->json(['category' => $category, 'items' => $items]);
    }
    public function update(Request $request, $id){
        if($request->hour_from)$request->merge(['hour_from' => Carbon::parse($request->hour_from)]);
        if($request->hour_to)$request->merge(['hour_to' => Carbon::parse($request->hour_to)]);
        $sellout = Sale::find($id);
        $request->request->set('last_attempt', Carbon::now());
        $request->validate([
            'place_id' => 'required|exists:places,id',
            'name' => 'required',
            'payment_type' => 'required',
            'lng' => 'required',
            'city' => 'required',
            'street' => 'required',
            'postal_code' => 'required',
            'hour_from' => 'required|date|before:hour_to',
            'hour_to' => 'required|date|after:hour_from',
            'products' => 'required'
        ]);
        if(!$sellout) return response()->json(['message' => 'Nie odnaleziono sprzedaży'], 400);
        if($request->user('api')->id != $sellout->place->user_id) return response()->json(['message' => 'Nie masz uprawnien'], 403);
        $sale = Sale::create($request->all());
        DB::table('sale_items')->where('sale_id', $sale->id)->delete();
        foreach ($request->products as $product){
            $item = [
                'item_id' => $product['id'],
                'sale_id' => $sale->id,
                'active' => true,
            ];
            DB::table('sale_items')->insert($item);
        }
        return response()->json($sale->load('items'));
    }
    public function store(Request $request){
        if($request->hour_from)$request->merge(['hour_from' => Carbon::parse($request->hour_from)]);
        if($request->hour_to)$request->merge(['hour_to' => Carbon::parse($request->hour_to)]);
        $request->request->set('last_attempt', Carbon::now());
        $request->request->set('hash', Hash::make(Str::random(30)));
        $request->validate([
            'place_id' => 'required|exists:places,id',
            'name' => 'required',
            'payment_type' => 'required',
            'lng' => 'required',
            'city' => 'required',
            'street' => 'required',
            'postal_code' => 'required',
            'hour_from' => 'required|date|before:hour_to',
            'hour_to' => 'required|date|after:hour_from',
            'products' => 'required'
        ]);
        $place = Place::find($request->place_id);
        if($request->user('api')->id != $place->user_id) return response()->json(['message' => 'Nie masz dostępu'], 403);
        $sale = Sale::create($request->all());
        foreach ($request->products as $product){
            $item = [
                'item_id' => $product['id'],
                'sale_id' => $sale->id,
                'active' => true,
            ];
            DB::table('sale_items')->insert($item);
        }
        return response()->json($sale->load('items'));
    }
    public function destroy(Request $request, Sale $sale){

        if(count($sale->orders()->where('status', 'new')->where('paid', 1)->get()) > 0) return response()->json(['message' => 'Nie możesz usunąć sprzedaży gdy są w niej niezrealizowane, opłacone zamówienia'], 400);
        if(count($sale->orders()->where('paid', 0)->where('created_at', '>=', Carbon::now()->subDays(2))->get()) > 0) return response()->json(['message' => 'Nie możesz usunąć sprzedaży gdy są w niej niezrealizowane, nieopłacone zamówienia które zostały złożone nie dawniej niż 2 dni temu'], 400);
        $sale->delete();
        return response()->json(true);
    }
}
