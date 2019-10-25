<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['model_id', 'model_name', 'tag'];
}
