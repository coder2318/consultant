<?php

namespace App\Observers;

use App\Events\NotificationEvent;
use App\Models\Notification;
use App\Models\Resume;

class ResumeObserver
{
    /**
     * Handle the Resume "created" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function creating()
    {
        // $resumes_count = Resume::where('profile_id', auth()->user()->profile->id)->get()->count();
        // if($resumes_count >= 3)
        //     abort(422, 'You have already 3 resumes');
    }

    public function created(Resume $resume)
    {
        $resume = Resume::find($resume->id);
        $data = [
            'profile_id' => $resume->profile_id,
            'text' => $resume->category,
            'type' => Notification::TYPE_RESUME,
            'link' => $resume->id,
            'data' => ['id' => $resume->id, 'status' => $resume->status, 'reason_inactive' => '']
        ];
        $notification = Notification::create($data);
        broadcast(new NotificationEvent($notification));
    }

    /**
     * Handle the Resume "updated" event.
     *
     * @param  \App\Models\Resume  $resume
     * @return void
     */
    public function updated(Resume $resume)
    {
        if($resume->wasChanged('status'))
        {
            $data = [
                'profile_id' => $resume->profile_id,
                'text' => $resume->category,
                'type' => Notification::TYPE_RESUME,
                'link' => $resume->id
            ];
            if($resume->status === Resume::CONFIRMED)
                $data['data'] = ['id' => $resume->id, 'status' => $resume->status, 'reason_inactive' => ''];
            else if($resume->status === Resume::BLOCKED)
                $data['data'] = ['id' => $resume->id, 'status' => $resume->status, 'reason_inactive' => $resume->reason_inactive];
            $notification = Notification::create($data);
            broadcast(new NotificationEvent($notification));
        }
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
