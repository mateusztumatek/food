<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['item_id', 'order_id', 'quantity', 'single_price', 'total_price', 'comment'];
    public function item(){
        return $this->belongsTo('App\Item');
    }
}
