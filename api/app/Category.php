<?php

namespace App;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use FilterTrait;
    protected $fillable = ['place_id', 'name', 'active', 'image'];
    public function place(){
        return $this->belongsTo('App\Place');
    }
    public function items(){
        return $this->belongsToMany('App\Item', 'item_categories', 'category_id', 'item_id');
    }
    public function scopeCustomGet($q){
        $q->get();
        return $q;
    }
    public function scopeWithProducts($q){
        $q->with('items');
        return $q;
    }
}
