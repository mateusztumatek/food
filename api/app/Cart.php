<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use mysql_xdevapi\Exception;

class Cart
{
    public $sale_id, $sale, $items, $price, $code, $discount_percentage, $discount_value;

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
        if($this->code){
            if($this->code->value){
                $this->price = $this->price - $this->code->value;
            }
            if($this->code->percentage){
                $this->price = $this->price - (($this->code->percentage / 100) * $this->price);
            }
        }
    }
    public function save($request){
       $request->session()->put('cart'.$this->sale_id, $this);
    }
    public function calculateTime(){
        return 30;
    }

    public function applyCode($code){
        $code = DiscountCode::where('sellout_id', $this->sale_id)->orWhere('place_id', $this->sale->place_id)->where('code', $code)->first();
        if($code){
            if($code->max_uses <= $code->uses) throw new \Exception('Wykorzystano maksymalną ilość tego kodu');
            $this->code = $code;
            $code->uses = $code->uses + 1;
            $code->update();
        }else{
            throw new \Exception('Code not exists or has been used');
        }
    }

    public function removeCode(){
        if($this->code){
            $code = DiscountCode::find($this->code->id);
            if($code){
                $code->uses = $code->uses - 1;
                $code->update();
            }
            $this->code = null;
            $this->discount_percentage = null;
            $this->discount_value= null;
        }
    }
}
