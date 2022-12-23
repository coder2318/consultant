<?php

namespace App\Observers;

use App\Events\NotificationEvent;
use App\Models\Application;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Notification;
use App\Models\Response;
use App\Models\Resume;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function created(Application $application)
    {
        if($application->resume_id){
            $application->update([
                'type' => Application::PRIVATE
            ]);
            $resume = Resume::find($application->resume_id);
            if($resume){
                $notification = Notification::create([
                    'profile_id' => $resume->profile_id,
                    'text' => $application->title,
                    'type' => Notification::TYPE_PRIVATE_APPLICATION,
                    'link' => $application->id,
                    'data' => ['status' => $application->status]
                ]);
                broadcast(new NotificationEvent($notification));
            }
        }
    }

    /**
     * Handle the Application "updated" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function updated(Application $application)
    {
        if($application->wasChanged('is_visible')){
            if($application->is_visible)
                DB::statement("update applications set status=".Application::PUBLISHED." where id=$application->id");
            else
                DB::statement("update applications set status=".Application::DRAFTED." where id=$application->id");
        }

        if($application->wasChanged('status') && $application->status == Application::CONFIRMED){
            DB::statement("update applications set payment_verified=true where id=$application->id");
            $response  = Response::where('application_id', $application->id)->where('resume_id', $application->resume_id)->first();
            if($response)
                $response->update([
                    'status' => Response::ACCEPT
                ]);


        }
    }

    /**
     * Handle the Application "deleted" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function deleted(Application $application)
    {
        Chat::where('application_id', $application->id)->delete();
    }

    /**
     * Handle the Application "restored" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function restored(Application $application)
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function forceDeleted(Application $application)
    {
        //
    }
}
