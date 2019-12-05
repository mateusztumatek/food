<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    protected $fillable = ['type', 'user_id', 'place_id', 'user_device', 'country', 'is_mobile', 'city', 'lat', 'lng', 'ip', 'session_key'];

    public function user(){
        return $this->belongsTo('App\User');
    }
    public function place(){
        return $this->belongsTo('App\Place');
    }
}
