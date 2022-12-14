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


Broadcast::channel('notifications.{profileId}', function ($user, $profileId) {
    return (int) $user->profile->id === (int) $profileId;
});

Broadcast::channel('invite.{profileId}', function ($user, $profileId) { /** chatga chaqriruvchi kanal */
    return (int) $user->profile->id === (int) $profileId;
});

Broadcast::channel('action-in-chats.{profileId}', function ($user, $profileId) { /** chatga chaqirilganda accept yoki not now qigandagi actionlarni chiqarib beruvchi kanal */
    return (int) $user->profile->id === (int) $profileId;
});

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    return in_array($user->profile->id, \App\Models\Chat\Chat::find($chatId)->profile_ids);
});

Broadcast::channel('discussion.{chatId}', function ($user, $chatId) {
    return in_array($user->profile->id, \App\Models\Chat\Chat::find($chatId)->profile_ids);
});

/** video chat uchun eventlar */
Broadcast::channel('video-channel.{chatId}', function ($user, $chatId) {
        if($user->canAccept($chatId)){
            return ['id' => $user->id, 'name' => $user->f_name, 'chat_id', $chatId];
        }
});
