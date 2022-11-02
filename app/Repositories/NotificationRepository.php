<?php


namespace App\Repositories;


use App\Models\Notification;

class NotificationRepository extends BaseRepository
{
    public function __construct(Notification $notification)
    {
        $this->entity = $notification;
    }
}