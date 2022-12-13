<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The chat instance.
     *
     * @var \App\Models\Chat\ChatMessage
     */
    public $chatMessage;
    public $to_profile_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($chatMessage, $to_profile_id)
    {
        $this->chatMessage = $chatMessage;
        $this->to_profile_id  = $to_profile_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        info('to_profile_id', [$this->to_profile_id]);
        info('chat_id', [$this->chatMessage->chat_id]);
        return [
            new PrivateChannel('chat.'.$this->chatMessage->chat_id),
            new PrivateChannel('notifications.'.$this->to_profile_id),
        ];

    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
