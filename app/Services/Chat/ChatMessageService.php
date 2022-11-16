<?php


namespace App\Services\Chat;


use App\Events\MessageSent;
use App\Events\MessageShowed;
use App\Repositories\Chat\ChatMessageRepository;
use App\Services\BaseService;
use Carbon\Carbon;

class ChatMessageService extends BaseService
{
    protected $chatService;

    public function __construct(ChatMessageRepository $repository, ChatService $chatService)
    {
        $this->repo = $repository;
        $this->filter_fields = [];
        $this->chatService = $chatService;
    }

    public function create($params, $is_response = false): object
    {
        if(!$is_response){
            $params['from_profile_id'] = auth()->user()->profile->id;
        }
        if($this->validForChat($params)){
            $chatMessage = $this->repo->store($params);
            broadcast(new MessageSent($chatMessage));
            return  $chatMessage;
        }
        abort(422,'Chat is invalid for sending|201');
    }

    public function updateShowed($params)
    {
        $query = $this->repo->getQuery();

        $messages = $query->whereIn('id',$params['message_ids'])->notShowed()->get();

        if($messages->count()){

            $messageQuery = $messages->toQuery();

            $messageQuery->update([
                'is_showed' => true,
                'showed_at' => Carbon::now()->toDateTimeString(),
            ]);

            $messages = $messageQuery->pluck('id');
            $chatID = $messageQuery->first()->chat_id;

            broadcast(new MessageShowed($messages, $chatID));

        }


        return $messages;
    }

    public function validForChat($params): bool
    {
        $chat = $this->chatService->show($params['chat_id']);
        return in_array(auth()->user()->profile->id,$chat->profile_ids);
    }
}