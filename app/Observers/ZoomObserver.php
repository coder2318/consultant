<?php

namespace App\Observers;

use App\Events\MessageSent;
use App\Models\Chat\ChatMessage;
use App\Models\Chat\Zoom;

class ZoomObserver
{
    /**
     * Handle the Zoom "created" event.
     *
     * @param  \App\Models\Chat\Zoom  $zoom
     * @return void
     */
    public function created(Zoom $zoom)
    {
        ChatMessage::create([
            'chat_id' => $zoom->chat_id,
            'from_profile_id' => $zoom->from_profile_id,
            'message' => '',
            'type' => ChatMessage::TYPE_CALL,
            'call_status' => Zoom::MISSED,
            'zoom_id' => $zoom->id
        ]);
    }

    /**
     * Handle the Zoom "updated" event.
     *
     * @param  \App\Models\Chat\Zoom  $zoom
     * @return void
     */
    public function updated(Zoom $zoom)
    {
        $chat_message = ChatMessage::where('type', ChatMessage::TYPE_CALL)->where('zoom_id', $zoom->id)->first();
        if($chat_message){
            if($chat_message->status !== $zoom->status){
                $chat_message->update([
                    'call_status' => $zoom->status
                ]);
                broadcast(new MessageSent($chat_message, $chat_message->chat->to_profile_id));
            }
        }
    }

    /**
     * Handle the Zoom "deleted" event.
     *
     * @param  \App\Models\Chat\Zoom  $zoom
     * @return void
     */
    public function deleted(Zoom $zoom)
    {
        //
    }

    /**
     * Handle the Zoom "restored" event.
     *
     * @param  \App\Models\Chat\Zoom  $zoom
     * @return void
     */
    public function restored(Zoom $zoom)
    {
        //
    }

    /**
     * Handle the Zoom "force deleted" event.
     *
     * @param  \App\Models\Chat\Zoom  $zoom
     * @return void
     */
    public function forceDeleted(Zoom $zoom)
    {
        //
    }
}
