<?php


namespace App\Repositories\Chat;


use App\Models\Chat\ChatMessage;
use App\Repositories\BaseRepository;

class ChatMessageRepository extends BaseRepository
{
    public function __construct(ChatMessage $chat)
    {
        $this->entity = $chat;
    }
}