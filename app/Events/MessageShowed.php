<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageShowed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messages;
    public $chatID;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($messages, $chatID)
    {
        $this->messages = $messages;
        $this->chatID = $chatID;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        info('message_showed', [$this->messages, $this->chatID]);
        return new PrivateChannel('chat.'.$this->chatID);
    }

    public function broadcastAs()
    {
        return 'message.showed';
    }
}