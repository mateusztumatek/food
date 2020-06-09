<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileOwner extends Model
{
    protected $fillable = ['user_id', 'path'];
}
