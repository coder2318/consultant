<?php

namespace App\Jobs;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $params;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $application_view = 'application_view_'.$this->params['id'].'_'.$this->params['profile_id'];
        info('applicatin_view', [$application_view]);
        $application = Application::find($this->params['id']);
        if (!Cache::has($application_view)) {
            $application->update([
                'views' => (int) $application->views + 1
            ]);
            Cache::put($application_view, $this->params['id'].'_'.$this->params['profile_id'], $seconds = 30);
        }
    }
}
