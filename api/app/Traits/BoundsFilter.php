<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

trait BoundsFilter{
    public function scopeBounds($q){
        $inputs = request()->all();
        $check = true;
        if(!array_key_exists('ne', $inputs) || !array_key_exists(0, $inputs['ne']) || !array_key_exists(1, $inputs['ne'])) $check = false;
        if(!array_key_exists('sw', $inputs) || !array_key_exists(0, $inputs['sw']) || !array_key_exists(1, $inputs['sw'])) $check = false;
        if($check){
           $q->where('lat', '>=', $inputs['sw'][1]);
           $q->where('lat', '<=', $inputs['ne'][1]);
            $q->where('lng', '>=', $inputs['sw'][0]);
            $q->where('lng', '<=', $inputs['ne'][0]);
        }
        return $q;
    }
}
