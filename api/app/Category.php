<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['place_id', 'name', 'active', 'image'];
    public function place(){
        return $this->belongsTo('App\Place');
    }
}
