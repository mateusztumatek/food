<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = ['lng', 'lat', 'city', 'postal_code', 'street', 'name', 'description', 'image'];
}
