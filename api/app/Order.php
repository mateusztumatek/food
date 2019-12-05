<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Order extends Model
{
    protected $fillable = ['user_id', 'sale_id', 'place_id', 'payment_link', 'payment_date', 'local_id', 'amount', 'comment', 'payment_type', 'paid', 'status', 'time', 'name', 'email', 'hash'];
   /* public $appends = ['items_count'];

    public function getItemsCountAttribute(){
        return $this->OrderItems()->count();
    }*/
    public function OrderItems(){
        return $this->hasMany('App\OrderItem')->with('item');
    }
    public function sale(){
        return $this->belongsTo('App\Sale');
    }
    public function place(){
        return $this->belongsTo('App\Place');
    }
    public function checkPermission(){
        if($this->user_id){
            if(request()->user('api') && request()->user('api')->id == $this->user_id) return true;
        }
        if(Session::has('orders')){
            $orders = Session::get('orders');
            foreach ($orders as $o){
                if($o->id == $this->id) return true;
            }
        }
        if(request()->user('api') && request()->user('api')->id == $this->sale->place->user_id) return true;
        return false;
    }
    public static function getOrders($request){
        $orders = collect();
        if($request->user('api')){
            $orders = Order::where('user_id', $request->user('api')->id)->where('created_at', '>=', Carbon::now()->subDay())->get();
        }
        if(Session::has('orders')){
            $temp = Session::get('orders');
            foreach ($temp as $key => $t){
                if($order = Order::find($t->id)){
                    $orders->push($order);
                }
            }
        }
        $orders = $orders->unique('id');
        return $orders;
    }
    public static function getNextId($sale_id){
        $t = Order::where('sale_id', $sale_id)->orderBy('created_at', 'desc')->first();
        ($t)? $temp_id = $t->local_id + 1 : $temp_id = 1;
        if($temp_id > 100) $temp_id = 1;
        $check = false;
        while(!$check){
            if(Order::where('local_id', $temp_id)->where('status', 'new')->first()) $temp_id = $temp_id + 1;
                else $check = true;
        }
        return $temp_id;
    }
}
