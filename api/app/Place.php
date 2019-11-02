<?php

namespace App;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use FilterTrait;
    protected $fillable = ['user_id','slug', 'lng', 'lat', 'city', 'postal_code', 'street', 'name', 'description', 'image'];
    /*public function getImageAttribute($value){
        return url('/framework/'.$value);
    }*/
    public function tags(){
        return $this->hasMany('App\Tag', 'model_id')->where('model_name', 'place');
    }
    public function products(){
        return $this->hasMany('App\Item', 'place_id');
    }
}
