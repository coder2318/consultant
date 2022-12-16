<?php


namespace App\Services\Chat;


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
        $zoom = $this->repo->getQuery()->where('chat_id', $params['chat_id'])->first();
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
}