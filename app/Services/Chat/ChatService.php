<?php


namespace App\Services\Chat;


use App\Models\Chat\ChatMessage;
use App\Repositories\Chat\ChatRepository;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;

class ChatService extends BaseService
{
    public function __construct(ChatRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = [
            'profile_ids' => ['type' => 'intarray'],
        ];
        $this->attributes = ['*'];
        $this->sort_fields = [
            'last_time' => 'desc'
        ];
        $this->relation = ['application:id,title,profile_id,category_id', 'messages'];
    }

    public function get(array $params, $pagination =  true)
    {
        //faqat oziga tegishli chatlarni royxatini olishi kerak
        $userID = auth()->user()->profile->id;
        $params['profile_ids'] = [$userID];

        $query = $this->repo->getQuery();

        if(isset($params['not_showed']) && $params['not_showed']){
            $query->whereHas('messages', function (Builder $builder){
                $builder->unread();
            });
        }

        $query->orderBy('last_time', 'desc');
        $query = $this->relation($query, $this->relation);
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->select($query, $this->attributes);
        return $query->get();

    }

    public function create($params): object
    {
        $to_profile_id = $params['to_profile_id'];
        $user  = auth()->user()->profile;
        $inputs['profile_ids'] = [$user->id, $to_profile_id];
        $inputs['application_id'] = $params['application_id'];
        $chat = $this->getByUserIds($inputs['profile_ids']);

        if(!$chat)
            $chat = $this->repo->store($inputs);

        return  $chat;
    }

    public function getByUserIds(array $userIDs, $operator = '@>')
    {
        $userIDs = "{" . implode(',', $userIDs) . "}";
        $chatQuery = $this->repo->getQuery();
        return $chatQuery->where('profile_ids', $operator,  $userIDs)->first();
    }

    public function sendResponseMessage($chat_id, $from_user_id, $response)
    {
        $chatMessage = $response->comment ?? 'response send';

        $chatMessage = $response->vacancy_id.'|'.$chatMessage;

        $inputs = [
            'chat_id' => $chat_id,
            'from_user_id' => $from_user_id,
            'message' => $chatMessage,
            'created_at' => date('Y-m-d H:i:s', strtotime($response->created_at)),
        ];

        ChatMessage::create($inputs);
    }
}