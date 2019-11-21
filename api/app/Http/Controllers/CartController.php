<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Item;
use App\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function deleteItem(Request $request, $id){
        $cart = Cart::getCartBySaleId($id, $request);
        $cart->deleteItem($request->id);
        $cart->refresh();
        $cart->save($request);
        return response()->json($cart);
    }
    public function updateItem(Request $request, $sale_id){
        $cart = Cart::getCartBySaleId($sale_id, $request);
        $request->validate([
            'id' => ['required', function($field, $data, $fail)use($cart){
                if(!array_key_exists($data, $cart->items->toArray())) $fail('Ten przedmiot nie istnieje w karcie');
            }],
            'quantity' => 'required|min:1'
        ]);
        if($request->clear_comment){
            $cart->items[$request->id]->comment = null;
        }else{
            $cart->updateItem($request->id, $request->quantity, $request->comment);
        }
        $cart->refresh();
        $cart->save($request);
        return response()->json($cart);
    }
    public function addItem(Request $request, $sale_id){
        if(!($sale = Sale::find($sale_id))) return response()->json(['message' => 'Nie ma takiej sprzedaży'], 400);
        $request->validate([
            'item_id' => ['required', 'exists:items,id'/*, function($field, $data, $fail)use($sale){
                $item = Item::find($data);
                if(!$item->active) $fail('Przedmiot ten nie jest aktywny w sprzedaży');
                if(!DB::table('sale_items')->where('sale_id', $sale->id)->where('item_id', $data)->first()) $fail('Nie ma takiego przedmiotu w sprzedaży');
            }*/],
            'quantity' => 'required|min:1'
        ]);
        $cart = Cart::getCartBySaleId($sale_id, $request);
        $item = Item::find($request->item_id);
        $item->quantity = $request->quantity;
        $cart->addItem($item);
        $cart->save($request);
        return response()->json($cart);
    }
    public function show(Request $request, $sale_id){
        return response()->json(Cart::getCartBySaleId($sale_id, $request));
    }
}
