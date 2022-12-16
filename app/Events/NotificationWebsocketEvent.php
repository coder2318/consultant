<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationWebsocketEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $profile_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($profile_id)
    {
        $this->profile_id = $profile_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('notifications.'.$this->profile_id);
    }

}
