<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Place;
use App\Sale;
use App\Services\PayuPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request){
        return response()->json(Order::getOrders($request));
    }
    public function store(Request $request){
        $request->validate([
            'sale_id' => ['required', function($field, $data, $fail)use($request){
                if(count(Cart::getCartBySaleId($request->sale_id, $request)->items) == 0) $fail('Brak przedmiotów w koszyku');
            }],
            'email' => 'required|email',
            'payment_type' => 'required'
        ]);
        $cart = Cart::getCartBySaleId($request->sale_id, $request);
        $local_id = Order::getNextId($request->sale_id);
        $sale = Sale::find($request->sale_id);
        $order = Order::create([
            'user_id' => ($request->user('api'))? $request->user('api')->id : null,
            'sale_id' => $request->sale_id,
            'place_id' => $sale->place->id,
            'amount' => $cart->price,
            'comment' => $request->comment,
            'payment_type' => $request->payment_type,
            'paid' => false,
            'status' => 'new',
            'time' => $cart->calculateTime(),
            'name' => $request->name,
            'email' => $request->email,
            'hash' => md5(Str::random(50)),
            'payment_link' => null,
            'payment_date' => null,
            'local_id' => $local_id
        ]);
        foreach ($cart->items as $item){
            $order->OrderItems()->create([
                'item_id' => $item->id,
                'order_id' => $order->id,
                'quantity' => $item->quantity,
                'single_price' => $item->price,
                'total_price' => $item->price * $item->quantity,
                'comment' => $item->comment
            ]);
        }
        $order->load('OrderItems');
        $session_orders = Session::get('orders');
        if(!$session_orders || get_class($session_orders) != 'Illuminate\Support\Collection'){
            $session_orders = collect();
        }
        $session_orders->push($order);
        Session::put('orders', $session_orders);
        if($order->payment_type == 'payu'){
            $payu_service = new PayuPayment();
            try{
                $return = $payu_service->makePayment($order);
            }catch(\Exception $e){
                return response()->json(['message' => 'Nie udało się utworzyc płatności online. Zapłać przy kasie', 'order' => $order], 400);
            }

            return response()->json($return);
        }

        return response()->json($order);
    }
    public function update(Request $request, $id){
        $order = Order::find($id);
        if(!$order) return response()->json(['errors' => 'Nie znaleziono zamówienia'], 400);
        if($request->user('api')->id != $order->sale->place->user_id) return response()->json(['message' => 'Nie masz uprawnień'], 400);
        $request->validate([
            'status' => 'required',
            'paid' => 'required'
        ]);

        $order->update($request->all());
        return response()->json($order->load('OrderItems', 'sale'));
    }
    public function show(Request $request, $hash){
        $order = Order::where('hash', $hash)->first();
        if(!$order->checkPermission()) return response()->json(['Nie masz pozwolenia do oglądania tego zamówienia'], 403);
        return response()->json($order->load('sale', 'OrderItems'));
    }
    public function getSelloutOrders(Request $request, $sale_id){
        $sale = Sale::find($sale_id);
        if(!$sale) return response()->json(['errors' => 'Nie znaleziono sprzedaży'], 400);
        if(Auth::id() != $sale->place->user_id) return respons()->json(['message' => 'Nie masz uprwanień'], 403);
        $orders = Order::where('sale_id', $sale->id);
        if($request->status){

            $orders = $orders->where('status', $request->status);
        }
        $orders = $orders->with('OrderItems')->get();
        return response()->json($orders);
    }
    public function getUserCustomersOrders(Request $request){
        /*$place = Place::find($place_id);
        if(!$place) return response()->json(['errors' => 'Nie znaleziono miejsca'], 400);*/
        if(!$request->user('api')->id) return response()->json(['errors' => 'Nie masz uprawnień'], 403);
        $places = Place::where('user_id', $request->user('api')->id)->get();
        $orders = Order::where(function ($q)use($places){
            foreach ($places as $place){
                $q->orWhere('place_id', $place->id);
            }
        })->with('sale', 'place', 'orderItems');
        if($request->amount_from) $orders = $orders->where('amount', '>=', $request->amount_from);
        if($request->amount_to) $orders = $orders->where('amount', '<=', $request->amount_to);
        if($request->date_from) $orders = $orders->where('created_at', '>=', Carbon::parse($request->date_from));
        if($request->date_to) $orders = $orders->where('created_at', '<=', Carbon::parse($request->date_to));
        $orders = $orders->orderBy('created_at', 'desc');
        $orders = $orders->paginate(($request->perPage)? $request->perPage : 10);
        return response()->json($orders);

    }
    public function notify(Request $request){
        Log::info(print_r($request->all(), true));
    }

}
