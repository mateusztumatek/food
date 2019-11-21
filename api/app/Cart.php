<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class Cart
{
    public $sale_id, $sale, $items, $price, $code;

    public function addItem($item){
        if($item){
            $existing = $this->items->where('id', $item->id);
            if(count($existing) > 0){
                $existing = $existing[0];
                $this->price = $this->price + $item->price * $item->quantity;
                $itemIndex = $this->items->search(function($item)use($existing) {
                    return $item->id === $existing->id;
                });
                $this->items[$itemIndex]->quantity = $this->items[$itemIndex]->quantity + $item->quantity;
            }else{
                $this->items->push($item);
                $this->price = $this->price + (floatval($item->price)* $item->quantity);
            }
        }
    }
    public function updateItem($index, $quantity, $comment){
        $this->items[$index]->comment = $comment;
       $this->items[$index]->quantity = $quantity;
    }
    public function deleteItem($id){
        $item = $this->items->get($id);
        if($item){
            $this->price = $this->price - ($item->price * $item->quantity);
        }
        $this->items->forget($id);
    }
    public static function getCartBySaleId($sale_id, $request){
        if($request->session()->has('cart'.$sale_id)){
            $cart = $request->session()->get('cart'.$sale_id);
        }else{
            $cart = new Cart();
            $cart->items = collect();
            $cart->price = 0;
            $cart->sale_id = $sale_id;
            $cart->sale = Sale::with('place')->find($sale_id);
            $request->session()->put('cart'.$sale_id, $cart);
        }
        return $cart;
    }
    public function refresh(){
        $this->price = 0;
        if(count($this->items) == 0){
            $this->price = 0;
            return;
        }
        foreach ($this->items as $item){
            $this->price = $this->price + $item->price * $item->quantity;
        }
    }
    public function save($request){
       $request->session()->put('cart'.$this->sale_id, $this);
    }
    public function calculateTime(){
        return 30;
    }
}
