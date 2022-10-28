<?php

namespace App\Observers;

use App\Models\Resume;

class ResumeObserver
{
    /**
     * Handle the Resume "created" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function creating(Resume $resume)
    {
        $resumes_count = Resume::where('profile_id', auth()->user()->profile->id)->get()->count();
        if($resumes_count >= 3)
            abort(422, 'You have already 3 resumes');
    }

    /**
     * Handle the Resume "updated" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function updated(Resume $resume)
    {
        //
    }

    /**
     * Handle the Resume "deleted" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function deleted(Resume $resume)
    {
        //
    }

    /**
     * Handle the Resume "restored" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function restored(Resume $resume)
    {
        //
    }

    /**
     * Handle the Resume "force deleted" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function forceDeleted(Resume $resume)
    {
        //
    }
}
