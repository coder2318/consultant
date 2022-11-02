<?php

namespace App\Services;

use App\Models\Chat\Chat;
use App\Models\Resume;
use App\Repositories\ResponseRepository;

class ResponseService extends BaseService
{
    public function __construct(ResponseRepository $repository, protected Chat $chatModel, protected Resume $resumeModel)
    {
        $this->repo = $repository;
        $this->filter_fields = [];
    }

    public function list(array $params)
    {
        $resume_ids = $this->resumeModel::where('profile_id', auth()->user()->profile->id)->get()->pluck('id');
        $query = $this->repo->getQuery();
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $query->whereIn('resume_id', $resume_ids);
        $query = $this->relation($query, $this->relation);
        $query = $this->select($query, $this->attributes);
        $query = $this->sort($query, $this->sort_fields, $params);
        if(isset($params['limit']))
            $query = $query->limit($params['limit']);

        return $query->get();
    }

    public function redirectToChat($response)
    {
        $resume = $this->resumeModel->where('id', $response->resume_id)->first();
        return $this->chatModel->where('application_id', $response->application_id)->where('profile_ids', '&&', '{'.auth()->user()->profile->id. ','.$resume->profile_id.'}')->first();
    }
}
