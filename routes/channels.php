<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.{sender}.{reciver}', function ($user){
    return !is_null($user);
    //    return !is_null($user);
});
//Broadcast::routes(['prefix'=>'api/v1', 'middleware'=>['auth:sanctum']]);
