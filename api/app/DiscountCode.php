<?php

namespace App;

use App\Traits\FilterTrait;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use FilterTrait;
    protected $fillable = ['sellout_id', 'place_id', 'percentage', 'value', 'code', 'max_uses', 'uses'];

    public function sellout(){
        return $this->belongsTo('App\Sale', 'sellout_id');
    }
    public function place(){
        return $this->belongsTo('App\Place');
    }
}
