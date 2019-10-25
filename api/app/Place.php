<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['user_id', 'lng', 'lat', 'city', 'postal_code', 'street', 'name', 'description', 'image'];
    /*public function getImageAttribute($value){
        return url('/storage/'.$value);
    }*/
    public function tags(){
        return $this->hasMany('App\Tag', 'model_id')->where('model_name', 'place');
    }
}
