<?php

namespace App\Observers;

use App\Models\Application;
use App\Models\Chat\Chat;
use App\Models\Response;
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
        $application->update([
            'expired_date' => Carbon::now()->addDays(30)->format('Y-m-d')
        ]);
        if($application->resume_id)
                $application->update([
                    'type' => Application::PRIVATE
                ]);
    }

    /**
     * Handle the Application "updated" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function updated(Application $application)
    {
        /** agar birorta consultantga alohida yuborilsa unda private type buladi */
        // if($application->wasChanged('resume_id')){
        //     if($application->resume_id)
        //         DB::statement("update applications set type=".Application::PRIVATE." where id=$application->id");
        //     else
        //         DB::statement("update applications set type=".Application::PUBLIC." where id=$application->id");

        // }

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
