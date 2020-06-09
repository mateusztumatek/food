<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('SessionChannel.{session_key}', function ($session_key) {
    return (int) $session_key === (int) \Illuminate\Support\Facades\Request::header('session-key');
});
Broadcast::channel('SaleChannel.{id}', function ($order){
    return response()->json($order);
});
