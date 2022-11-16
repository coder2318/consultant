<?php


namespace App\Repositories\Chat;


use App\Models\Chat\Chat;
use App\Repositories\BaseRepository;

class ChatRepository extends BaseRepository
{
    public function __construct(Chat $chat)
    {
        $this->entity = $chat;
    }
}