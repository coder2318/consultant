<?php

namespace App\Services;

use App\Jobs\ApplicationJob;
use App\Models\Application;
use App\Models\Chat\ChatMessage;
use App\Models\Response;
use App\Models\Resume;
use App\Repositories\ApplicationRepository;
use App\Services\Chat\ChatMessageService;
use App\Traits\FilesUpload;
use Illuminate\Database\Eloquent\Builder;

class ApplicationService extends BaseService
{
    use FilesUpload;
    public function __construct(ApplicationRepository $repo, protected ChatMessageService $chatMessageService)
    {
        $this->repo = $repo;
        $this->filter_fields = ['title' => ['type' => 'string'], 'resume_id' => ['type' => 'number'], 'application_id' => ['type' => 'number'],
                'category_id' => ['type' => 'number'], 'price_from' => ['type' => 'from'], 'price_to' => ['type' => 'to'],
                'when_date' => ['type' => 'notNull'], 'profile_id' => ['type' => 'number'], 'status' => ['type' => 'number'],
                'type' => ['type' => 'number']
            ];
        $this->attributes = [
            'id', 'description', 'status', 'files', 'created_at', 'type', 'price_from', 'price_to', 'title', 'profile_id',
            'category_id', 'showed', 'reason_inactive', 'when_date', 'views', 'is_visible'
        ];
        $this->relation = ['response:id,application_id,resume_id'];
    }

    public function selfIndex(array $params, $pagination = true)
    {
        $perPage = null;
        if ($pagination) {
            $perPage = isset($params['per_page']) ? $params['per_page'] : 20;
        }

        $resumes = Resume::where('profile_id', auth()->user()->profile->id)->get();
        $params['self_category_ids'] = $resumes->pluck('category_id');
        $query = $this->repo->getQuery();
        $query = $this->selfCategory($query, $params);
        $query = $this->relation($query, $this->relation);
        $query = $this->searchByTitle($query, $params);

        if(isset($params['response_status'])){
            $query = $query->whereHas('response', function(Builder $q) use ($params, $resumes) {
                $resume_ids = $resumes->pluck('id');
                $q->whereIn('resume_id', $resume_ids);
            });
        }

        if(isset($params['type']) && $params['type'] == Application::PRIVATE){
            $resume_ids = $resumes->pluck('id');
            $query = $query->whereIn('resume_id', $resume_ids);
        }

        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $query->publish();
        $query = $this->sort($query, $this->sort_fields, $params);
        $query = $this->select($query, $this->attributes);
        $query = $this->repo->getPaginate($query, $perPage);
        return $query;
    }

    public function selfCategory($query, $params)
    {
        if(!isset($params['category_id']))
            $query = $query->whereIn('category_id', $params['self_category_ids']);
        return $query;
    }

    public function list(array $params)
    {
        $query = $this->repo->getQuery();
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $query->withoutGlobalScope('visible');
        $query = $this->searchByTitle($query, $params);
        $query = $this->relation($query, $this->relation);
        $query = $this->select($query, $this->attributes);
        $query = $this->sort($query, $this->sort_fields, $params);
        if(isset($params['limit']))
            $query = $query->limit($params['limit']);

        return $query->get();
    }

    public function myOrderIndex(array $params, $pagination = true)
    {
        $perPage = null;
        if ($pagination) {
            $perPage = isset($params['per_page']) ? $params['per_page'] : 20;
        }
        $query = $this->repo->getQuery();
        $resume_ids = Resume::where('profile_id', auth()->user()->profile->id)->get()->pluck('id');
        $responses_application_ids = Response::whereIn('resume_id', $resume_ids)->where('status', Response::ACCEPT)->get()->pluck('application_id');

        $query = $query->where(function ($q) use ($resume_ids, $responses_application_ids){
            $q->whereIn('id', $responses_application_ids)->orWhereIn('resume_id', $resume_ids);
        });

        $query = $this->searchByTitle($query, $params);
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $query->confirm();
        $query = $this->sort($query, $this->sort_fields, $params);
        $query = $this->select($query, $this->attributes);
        $query = $this->repo->getPaginate($query, $perPage);
        return $query;
    }

    public function create($params): object
    {
        if(isset($params['resume_id'])){
            $to_profile_id = Resume::find($params['resume_id'])->profile_id;
            if($to_profile_id === auth()->user()->profile->id)
                abort(403, 'You don\'t send application to your resume');
        }
        $params = $this->fileUpload($params, 'applications');
        return $this->repo->store($params);
    }

    public function edit($params, $id): mixed
    {
        $application = $this->repo->getById($id);
        $params = $this->fileUpload($params, 'applications', $application);
        if(isset($params['to_profile_id']) && isset($params['status'])){
            $resume_ids = Resume::where('profile_id', $params['to_profile_id'])->get()->pluck('id');
            $response_resume = Response::where('application_id', $id)->whereIn('resume_id', $resume_ids)->first();
            if($response_resume)
                $params['resume_id'] = $response_resume->resume_id;
        }
        $application = $this->repo->update($params, $id);

        if(isset($params['status']) && isset($params['chat_id']))
            $this->changeStatus($id, $params['chat_id']);

        return $application;
    }

    public function changeStatus($application_id, $chat_id)
    {
        $application = $this->repo->getById($application_id);
        $type = match ($application->status) {
            Application::CONFIRMED => 'accept',
            default => 'waiting'
        };

        $action_status = match ($application->status) {
            Application::CONFIRMED => ChatMessage::ACCEPT,
            Application::WAIT_CONFIRM => ChatMessage::WAIT,
            default => null
        };
        $data['msg'][] = [
            'message' => '',
            'is_price' => false,
            'action_status' => $action_status
        ];
        $data['chat_id'] = $chat_id;
        $this->chatMessageService->create($data);

        dealDataForm($type, $chat_id, $application->status, $application->payment_verified);
    }

    public function show($id)
    {
        $model = $this->repo->getById($id);
        ApplicationJob::dispatch([
            'id' => $id,
            'profile_id' => auth()->user()->profile->id
        ]);
        $model->response;
        return $model;
    }

    public function showAdmin($id)
    {
        $model = $this->repo->getById($id);
        $model->update([
            'showed' => true
        ]);
        return $model;
    }

    public function myResponseApplication(array $params, $pagination = true)
    {
        $perPage = null;
        if ($pagination) {
            $perPage = isset($params['per_page']) ? $params['per_page'] : 20;
        }
        $resume_ids = Resume::where('profile_id', auth()->user()->profile->id)->get()->pluck('id');
        $application_ids = Response::whereIn('resume_id', $resume_ids)->get()->pluck('application_id');
        $query = $this->repo->getQuery();
        $query = $query->whereIn('id', $application_ids);
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->select($query, $this->attributes);
         return $this->repo->getPaginate($query, $perPage);
    }

    public function getCountBadges($params)
    {
        $resumes = Resume::where('profile_id', auth()->user()->profile->id)->get();
        $resume_ids = $resumes->pluck('id');
        $params['self_category_ids'] = $resumes->pluck('category_id');
        $application_ids = Response::whereIn('resume_id', $resume_ids)->get()->pluck('application_id');

        $response = $this->query($params)->whereIn('id', $application_ids)->where('status', Application::PUBLISHED)->get()->count();
        $immediately = $this->query($params)->whereNotNull('when_date')->get()->count();
        $private = $this->query($params)->where('type', Application::PRIVATE)->whereIn('resume_id', $resume_ids)->get()->count();

        return [
            'immediately' => $immediately,
            'private' => $private,
            'response' => $response
        ];
    }

    function query($params)
    {
        $query = $this->repo->getQuery();
        $query = $this->selfCategory($query, $params);
        $this->filter($query, $this->filter_fields, $params);
        return $query;
    }

    function searchByTitle($query, $params)
    {
        if(isset($params['search'])){
            $query = $query->where(function($q) use ($params){
                $q->where('title', 'ilike', '%'.$params['search'].'%')->orWhere('description', 'ilike', '%'.$params['search'].'%');
            });
        }
        return $query;
    }

    public function countStat()
    {
        $applications = $this->repo->getQuery()->active()->get()->count();
        $finished = $this->repo->getQuery()->finished()->get()->count();
        $resumes = Resume::active()->get()->count();
        return [
            'count_applications' => $applications + 25,
            'count_finished_applications' => $finished + 25,
            'count_resumes' => $resumes + 25
        ];
    }

}
