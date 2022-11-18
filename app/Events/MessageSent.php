<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat\ChatMessage;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The chat instance.
     *
     * @var \App\Models\Chat\ChatMessage
     */
    public $chatMessage;
    public $userID;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ChatMessage $chatMessage, $to_profile_id)
    {
        $this->chatMessage = $chatMessage;
        $this->userID  = $to_profile_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        info('conn', [$this->userID]);
        return [
            new PrivateChannel('chat.'.$this->chatMessage->chat_id),
            new PrivateChannel('notifications.'.$this->userID),
        ];

    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
