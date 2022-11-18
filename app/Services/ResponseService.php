<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Resume;
use App\Repositories\ResponseRepository;
use App\Services\Chat\ChatService;
use Illuminate\Support\Facades\DB;

class ResponseService extends BaseService
{
    public function __construct(ResponseRepository $repository, protected Chat $chatModel, protected Resume $resumeModel,
                                protected Application $applicationModel, protected ChatService $chatService)
    {
        $this->repo = $repository;
        $this->filter_fields = [];
    }

    public function create($params): object
    {
        DB::beginTransaction();
            $response = $this->repo->store($params);
            $application = $this->applicationModel->find($response->application_id);
            $inputs['profile_ids'] = [auth()->user()->profile->id, $application->profile_id];
            $inputs['application_id'] = $application->id;
            $chat = $this->chatService->getByUserIds($inputs['profile_ids']);
            if(!$chat){
                $chat = $this->chatService->repo->store($inputs);
            }
            $messages = [
                ['message' => $response->text, 'is_price' => false],
                ['message' => $response->amount, 'is_price' => true]
            ];
            foreach ($messages as $msg){
                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'from_profile_id' => auth()->user()->profile->id,
                    'message' => $msg['message'],
                    'is_price' => $msg['is_price']
                ]);
            }
        DB::commit();
            return $response;
    }

    public function redirectToChat($response)
    {
        $application = $this->applicationModel->find($response->application_id);
        return $this->chatModel->where('application_id', $response->application_id)->where('profile_ids', '&&', '{'.auth()->user()->profile->id. ','.$application->profile_id.'}')->first();
    }

    public function checkResponse($application_id)
    {
        $resume_ids = $this->resumeModel->where('profile_id', auth()->user()->profile->id)->get()->pluck('id');
        $response = $this->repo->getQuery()->where('application_id', $application_id)->whereIn('resume_id', $resume_ids)->get()->count();
        if($response > 0)
            return true;
        return false;
    }
}
