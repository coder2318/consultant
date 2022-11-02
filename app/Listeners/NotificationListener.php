<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        $data = $event->params;
        Notification::create([
            'profile_id' => $data['profile_id'],
            'text' => $data['text'] ?? 'Notification',
            'description' => $data['description']?? null,
            'type' => $data['type'],
            'data' => $data['data']
        ]);
    }
}
