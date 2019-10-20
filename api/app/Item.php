<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['sale_id', 'name', 'description', 'image', 'price', 'prepere_time', 'active'];
}
