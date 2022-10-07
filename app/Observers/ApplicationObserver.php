<?php

namespace App\Observers;

use App\Models\Application;
use Carbon\Carbon;

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
    }

    /**
     * Handle the Application "updated" event.
     *
     * @param  \App\Models\Application  $application
     * @return void
     */
    public function updated(Application $application)
    {
        if($application->wasChanged('when')){
            $when_date = match ($application->when){
                Application::TODAY => Carbon::now()->format('Y-m-d'),
                Application::TOMORROW => Carbon::now()->addDay()->format('Y-m-d'),
                Application::IN_WEEK => Carbon::now()->addWeek()->format('Y-m-d')
            };
            $application->update([
                'when_date' => $when_date
            ]);
        }
        /** agar birorta consultantga alohida yuborilsa unda private type buladi */
        if($application->wasChanged('resume_id')){
            if($application->resume_id)
                $application->update([
                    'type' => Application::PRIVATE
                ]);
            else
                $application->update([
                    'type' => Application::PUBLIC
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
