<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Chat\Chat;
use App\Models\Chat\ChatMessage;
use App\Models\Response;
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
            $application = $this->applicationModel->find($params['application_id']);
            if($application->profile_id == auth()->user()->profile->id){
                abort(403, 'You don\'t send response to your application');
            }
            $response = $this->repo->store($params);
            $inputs['profile_ids'] = [auth()->user()->profile->id, $application->profile_id];
            $inputs['application_id'] = $application->id;
            $chat = $this->chatService->getByUserIds($inputs['profile_ids'], $application->id);
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
                    'is_price' => $msg['is_price'],
                    'action_status' => $msg['is_price'] === true ? ChatMessage::OFFER : null
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
        $application = Application::find($application_id);
        $chat = Chat::where('application_id', $application_id)->where('profile_ids', '&&', '{'.auth()->user()->profile->id. ','.$application->profile_id.'}')->first();
        if($response > 0 && $chat)
            return [
                'chat_id' => $chat->id,
                'check_response' => true
            ];
        else if($chat)
            return [
                'chat_id' => $chat->id,
                'check_response' => true
            ];
        else
            return [
            'chat_id' => null,
            'check_response' => false
        ];
    }

    public function createChat($params)
    {
        DB::beginTransaction();
        $application = $this->applicationModel->newQueryWithoutScopes()->find($params['application_id']);
        $inputs['profile_ids'] = [auth()->user()->profile->id, $application->profile_id];
        $inputs['application_id'] = $application->id;
        $chat = $this->chatService->getByUserIds($inputs['profile_ids'], $application->id);
        if(!$chat){
            $chat = $this->chatService->repo->store($inputs);
        }
        foreach ($params['msg'] as $msg){
            ChatMessage::create([
                'chat_id' => $chat->id,
                'from_profile_id' => auth()->user()->profile->id,
                'message' => $msg['message'],
                'is_price' => $msg['is_price'],
                'action_status' => $msg['is_price'] === true ? ChatMessage::OFFER : null
            ]);
        }
        DB::commit();
        return $chat;
    }

    public function cancelResponse($params)
    {
        $resume_ids = Resume::where('profile_id', $params['profile_id'])->get()->pluck('id');
        $response = $this->repo->getQuery()->where('application_id', $params['application_id'])->whereIn('resume_id', $resume_ids)->first();
        if($response) {
            $response->update([
                'status' => Response::DENY
            ]);
            ChatMessage::create([
                'from_profile_id' => auth()->user()->profile->id,
                'chat_id' => $params['chat_id'],
                'action_status' => ChatMessage::DENY
            ]);
//            dealDataForm('deny', $params['chat_id'], Response::DENY, false, auth()->user()->profile->id);
            return $response;
        }
    }
}
