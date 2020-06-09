<?php
namespace App\Services;
class PayuHistory{
    public $date, $title, $desc;
    public static function create($date, $title, $desc){
        $history = new PayuHistory();
        $history->date = $date;
        $history->title = $title;
        $history->desc = $desc;
        return $history;
    }
}