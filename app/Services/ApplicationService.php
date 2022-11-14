<?php

namespace App\Services;

use App\Jobs\ApplicationJob;
use App\Models\Response;
use App\Models\Resume;
use App\Repositories\ApplicationRepository;
use App\Traits\FilesUpload;

class ApplicationService extends BaseService
{
    use FilesUpload;
    public function __construct(ApplicationRepository $repo)
    {
        $this->repo = $repo;
        $this->filter_fields = ['resume_id' => ['type' => 'number'], 'application_id' => ['type' => 'number'],
                'category_id' => ['type' => 'number'], 'price_from' => ['type' => 'from'], 'price_to' => ['type' => 'to'],
                'when_date' => ['type' => 'notNull'], 'profile_id' => ['type' => 'number'], 'status' => ['type' => 'number'],
                'type' => ['type' => 'number']
            ];
        $this->attributes = [
            'id', 'description', 'status', 'files', 'created_at', 'type', 'price_from', 'price_to', 'title', 'profile_id', 
            'category_id', 'showed', 'reason_inactive', 'when_date', 'views', 'is_visible'
        ];
        $this->relation = ['response'];
    }

    public function selfIndex(array $params, $pagination = true)
    {
        $perPage = null;
        if ($pagination) {
            $perPage = isset($params['per_page']) ? $params['per_page'] : 20;
        }

        $query = $this->repo->getQuery();
        $self_category_id = Resume::where('profile_id', auth()->user()->profile->id)->get()->pluck('category_id');

        if(!isset($params['category_id']))
            $query = $query->whereIn('category_id', $self_category_id);

        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->sort($query, $this->sort_fields, $params);
        $query = $this->select($query, $this->attributes);
        $query = $this->repo->getPaginate($query, $perPage);
        return $query;
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
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $query->confirm();
        $query = $this->sort($query, $this->sort_fields, $params);
        $query = $this->select($query, $this->attributes);
        $query = $this->repo->getPaginate($query, $perPage);
        return $query;
    }

    public function create($params): object
    {
        $params = $this->fileUpload($params, 'applications');
        return $this->repo->store($params);
    }

    public function edit($params, $id): mixed
    {
        $application = $this->repo->getById($id);
        $params = $this->fileUpload($params, 'applications', $application);
        return $this->repo->update($params, $id);
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

    public function myResponseApplication(array $params)
    {
        $resume_ids = Resume::where('profile_id', auth()->user()->profile->id)->get()->pluck('id');
        $application_ids = Response::whereIn('resume_id', $resume_ids)->get()->pluck('application_id');
        $query = $this->repo->getQuery();
        $query = $query->whereIn('id', $application_ids);
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->select($query, $this->attributes)->get();
        $query->append('response_status');
        return $query;
    }

}
