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

    public function list(array $params)
    {
        $query = $this->repo->getQuery();
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->relation($query, $this->relation);
        $query = $this->select($query, $this->attributes);
        $query = $this->sort($query, $this->sort_fields, $params);
        if(isset($params['limit']))
            $query = $query->limit($params['limit']);
        $query = $query->get();
        $this->updateShowed(['message_ids' => $query->pluck('id')]);
        return $query;
    }

    public function create($params, $is_response = false): object
    {
        if(!$is_response){
            $input['from_profile_id'] = auth()->user()->profile->id;
        }
        if($this->validForChat($params)){
            $input['chat_id'] = $params['chat_id'];
            foreach ($params['msg'] as $item){
                $input['message'] = $item['message'];
                $input['is_price'] = $item['is_price'];
                $chatMessage = $this->repo->store($input);
            }
            $to_profile_id = $chatMessage->chat->to_profile_id;
            broadcast(new MessageSent($chatMessage, $to_profile_id));
            return  $chatMessage;
        }
        abort(422,'Chat is invalid for sending|201');
    }

    public function updateShowed($params)
    {
        $query = $this->repo->getQuery();

        $messages = $query->where('from_profile_id', '!=', auth()->user()->profile->id)->whereIn('id',$params['message_ids'])->notShowed()->get();

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