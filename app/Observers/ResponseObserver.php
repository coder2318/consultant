<?php

namespace App\Observers;

use App\Events\NotificationEvent;
use App\Models\Application;
use App\Models\Notification;
use App\Models\Response;

class ResponseObserver
{
    /**
     * Handle the Response "created" event.
     *
     * @param  \App\Models\Response  $response
     * @return void
     */
    public function created(Response $response)
    {
        $application = Application::find($response->application_id);
        $notification = Notification::create([
            'profile_id' => $application->profile_id,
            'text' => $application->title,
            'type' => Notification::RESPONSE,
            'link' => $application->id,
            'data' => ['status' => $application->status]
        ]);
        broadcast(new NotificationEvent($notification));
    }

    /**
     * Handle the Response "updated" event.
     *
     * @param  \App\Models\Response  $response
     * @return void
     */
    public function updated(Response $response)
    {
        //
    }

    /**
     * Handle the Response "deleted" event.
     *
     * @param  \App\Models\Response  $response
     * @return void
     */
    public function deleted(Response $response)
    {
        //
    }

    /**
     * Handle the Response "restored" event.
     *
     * @param  \App\Models\Response  $response
     * @return void
     */
    public function restored(Response $response)
    {
        //
    }

    /**
     * Handle the Response "force deleted" event.
     *
     * @param  \App\Models\Response  $response
     * @return void
     */
    public function forceDeleted(Response $response)
    {
        //
    }
}
