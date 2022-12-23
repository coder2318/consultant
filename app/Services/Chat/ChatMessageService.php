<?php


namespace App\Services\Chat;


use App\Events\MessageSent;
use App\Events\MessageShowed;
use App\Events\NotificationEvent;
use App\Models\Application;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Notification;
use App\Models\Profile;
use App\Models\Response;
use App\Repositories\Chat\ChatMessageRepository;
use App\Services\BaseService;
use App\Traits\FilesUpload;
use Carbon\Carbon;

class ChatMessageService extends BaseService
{
    use FilesUpload;
    protected $chatService;

    public function __construct(ChatMessageRepository $repository, ChatService $chatService)
    {
        $this->repo = $repository;
        $this->filter_fields = ['chat_id' => ['type' => 'number']];
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
        /** last chat of messages for event socket */

        $last_chat = $query->first();
        if($last_chat){
            $chat = Chat::find($last_chat->chat_id);
            $application = Application::find($chat->application_id);
            info('$application->response_status', [$application->response_status]);
                if($application->response_status == Response::DENY)
                    dealDataForm('deny', $last_chat->chat_id, $application->response_status, false, $last_chat->from_profile_id);
                else
                    dealDataForm('offer', $last_chat->chat_id, $application->status, $application->payment_verified, $last_chat->from_profile_id);
        }

        return $query;
    }

    public function create($params, $is_response = false): object
    {
        if($this->validForChat($params)){
            $chatMessage = $this->repo->getQuery()->orderBy('id', 'desc')->first();
            foreach ($params['msg'] as $item){
                $input['message'] = $item['message']??'';
                $input['is_price'] = $item['is_price'];
                if(isset($item['file'])){
                    $input = $this->fileUpload($item, 'chat-messages');
                    $input['type'] = ChatMessage::TYPE_FILE;
                }
                $input['chat_id'] = $params['chat_id'];
                $input['from_profile_id'] = auth()->user()->profile->id;
                $input['action_status'] = $item['action_status'] ?? null;
                $chatMessage = $this->repo->store($input);
                $to_profile_id = $chatMessage->chat->to_profile_id;
                broadcast(new MessageSent($chatMessage, $to_profile_id));
            }
            $this->sendNotificationEvent($chatMessage);
            if($chatMessage && $chatMessage->is_price === "true"){
                info('$chatMessage->is_price', [$chatMessage->is_price]);
                dealDataForm('offer', $chatMessage->chat_id, Application::PUBLISHED, false, $chatMessage->from_profile_id);
            }
            return  $chatMessage;
        }
        abort(422,'Chat is invalid for sending|201');
    }

    function sendNotificationEvent($chatMessage)
    {
        $application = Application::find($chatMessage->chat->application_id);
        $notification = Notification::create([
            'profile_id' => $chatMessage->chat->to_profile_id,
            'text' => $application ? $application->title : '',
            'type' => Notification::TYPE_MESSAGE,
            'link' => $chatMessage->chat_id,
            'data' => ['status' => ChatMessage::TYPE_CHAT]
        ]);
        broadcast(new NotificationEvent($notification));
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