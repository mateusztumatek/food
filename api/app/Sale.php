<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Place
{
    protected $fillable;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable = parent::fillable();
        array_push($this->fillable, 'hour_from');
        array_push($this->fillable, 'hour_to');
        array_push($this->fillable, 'place_id');
    }
}
