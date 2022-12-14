<?php


namespace App\Services\Chat;


use App\Events\ActionsInChatsEvent;
use App\Events\InviteChat;
use App\Models\Chat\Chat;
use App\Models\Chat\Zoom;
use App\Repositories\Chat\ZoomRepository;
use App\Services\BaseService;
use Carbon\Carbon;

class ZoomService extends BaseService
{
    public function __construct(ZoomRepository $repository, protected Chat $chatModel)
    {
        $this->repo = $repository;
    }

    public function create($params): object
    {
        $chat = $this->chatModel->find($params['chat_id']);
        if($chat){
            $data = [
                'profile_ids' => $chat->profile_ids,
                'chat_id' => $chat->id,
            ];
            return $this->repo->store($data);
        }
    }

    public function changeStatus($params, $status)
    {
        $zoom = $this->repo->getQuery()->where('chat_id', $params['chat_id'])->latest()->first();
        if($zoom){
            $data = [
                'status' => $status,
            ];
            return $this->repo->update($data, $zoom->id);
        }
    }

    public function update($params)
    {
        $zoom = $this->repo->getQuery()->where('chat_id', $params['chat_id'])->latest()->first();
        if($zoom){
            $data = [
                'end_time' => Carbon::now(),
            ];
            return $this->repo->update($data, $zoom->id);
        }
    }

    public function inviteToChat($chat_id)
    {
        $chat = $this->chatModel->findOrFail($chat_id);
        $data = [
            'profile_id' => $chat->to_profile_id,
            'is_consultant' => !(auth()->user()->profile->id == $chat->application->profile_id),
            'text' => $chat->application->title,
            'chat_id' => $chat_id
        ];
        broadcast(new InviteChat($data));
        return $data;
    }

    public function actionInChat($params)
    {
        $chat = $this->chatModel->findOrFail($params['chat_id']);
        $data = [
            'profile_id' => $chat->to_profile_id,
            'fullname' => auth()->user()->l_name . ' '.auth()->user()->f_name,
            'type' => $params['type']
        ];
        broadcast(new ActionsInChatsEvent($data));
        return $data;
    }
}