<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['place_id', 'name', 'description', 'image', 'price', 'prepere_time', 'active'];
    public function getImageAttribute($image){
        if($image) return $image;
            else return 'products/placeholder.png';
    }
    public function categories(){
        return $this->belongsToMany('App\Category', 'item_categories', 'item_id', 'category_id');
    }
    public function tags(){
        return $this->hasMany('App\Tag', 'model_id')->where('model_name', 'item');
    }
    public function place(){
        return $this->belongsTo('App\Place');
    }
}
