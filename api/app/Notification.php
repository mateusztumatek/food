<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Notification extends Model
{
    protected $fillable = ['type', 'user_id', 'session_key', 'link', 'seen', 'data'];
    protected $appends = ['data'];

    public function getDataAttribute($field){
        if($field){
            return json_decode($field);
        }
        return [];
    }
    public static function getNotifications(){
        $user = Request::user('api');
        $notifications = collect();
        if($user){
            $notifications = Notification::orderBy('created_at')->where('user_id', $user->id)->get();
        }
        $session_notifications = Notification::where('session_key', Request::header('session-key'))->get();
        $notifications->merge($session_notifications);
        $notifications = $notifications->unique('id');
        return $notifications;
    }
}
