<?php

namespace App\Observers;

use App\Models\Application;
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
    }

    /**
     * Handle the Application "deleted" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function deleted(Application $application)
    {
        //
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
