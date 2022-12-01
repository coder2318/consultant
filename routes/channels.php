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

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});


Broadcast::channel('notifications.{userId}', function ($user, $userId) {
    return (int) $user->profile->id === (int) $userId;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return in_array($user->profile->id, \App\Models\Chat\Chat::find($chatId)->profile_ids);
});

/** video chat uchun eventlar */
Broadcast::channel('client-video-channel', function ($user) {
        \Illuminate\Support\Facades\Log::info('Incoming_broadcast_auth', ['id' => $user->id, 'name' => $user->profile->id]);
        return ['id' => $user->id, 'name' => $user->f_name];
});
