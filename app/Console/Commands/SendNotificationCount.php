<?php

namespace App\Console\Commands;

use App\Events\NotificationEvent;
use App\Models\Application;
use App\Models\Notification;
use App\Models\Resume;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendNotificationCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newapplication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send to consultants about new application counts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $from = Carbon::now()->subHours(3);
        $to = Carbon::now();
        $applications = Application::where('status', Application::PUBLISHED)->whereBetween('created_at', [$from, $to])->groupBy('category_id')
            ->select('category_id', DB::raw("count(category_id) as count"))->get()->each->setAppends([]);
        if(count($applications)){
            foreach ($applications as $item)
            {
                $profile_ids = Resume::where('category_id', $item->category_id)->get()->pluck('profile_id')->toArray();
                if(count($profile_ids))
                    foreach ($profile_ids as $profile_id) {
                        $notification = Notification::create([
                            'profile_id' => $profile_id,
                            'text' => '',
                            'type' => Notification::TYPE_NEW_APPLICATIONS_COUNT,
                            'link' => $item->count,
                            'data' => [
                                'category_id' => $item->category_id
                            ]
                        ]);
                        broadcast(new NotificationEvent($notification));
                    }
            }

        }
        return true;
    }
}
