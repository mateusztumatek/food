<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class StatsItem extends Model {
    public $fillable = ['date', 'type', 'value'];
    public function __construct($date, $type, $value)
    {
        $this->date = $date;
        $this->type = $type;
        $this->value = $value;
    }
}