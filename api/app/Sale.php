<?php

namespace App;

use App\Helpers\Helper;
use App\Traits\BoundsFilter;
use App\Traits\FilterTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\Input;

class Sale extends Place
{
    use FilterTrait;
    use BoundsFilter;
    protected $fillable = ['hour_from', 'hour_to', 'place_id', 'payment_type', 'archivize', 'last_attempt', 'hash', 'expected_time', 'city', 'street', 'lng', 'lat', 'postal_code', 'name'];
    public $appends = ['is_attempted', 'distance', 'image'];

    public function getImageAttribute(){
        return $this->place->image;
    }
    public function getDistanceAttribute(){
        if(request()->withDistance && request()->user_lat && request()->user_lng){
            return Helper::distance(request()->user_lat, request()->user_lng, $this->lat, $this->lng);
        }else return null;
    }
    public function getIsAttemptedAttribute(){
        return Carbon::parse($this->last_attempt) > Carbon::now()->subMinutes(2);
    }
    public function place(){
        return $this->belongsTo('App\Place')->with('tags');
    }
    public function items(){
        return $this->belongsToMany('App\Item', 'sale_items', 'sale_id', 'item_id');
    }
}