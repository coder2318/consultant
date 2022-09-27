<?php

namespace App\Services;

use App\Repositories\ExperienceRepository;

class ExperienceService extends BaseService
{
    public function __construct(ExperienceRepository $repository)
    {
        $this->repo = $repository;
    }

    public function list(array $params)
    {
        $query = $this->repo->getQuery();
        $query = $query->where('resume_id', auth()->user()->profile->id);
        $query = $this->relation($query, $this->relation);
        $query = $this->select($query, $this->attributes);
        $query = $this->sort($query, $this->sort_fields, $params);
        return $query->get();
    }

    public function edit($params, $id): mixed
    {
        if(isset($params['is_current_job']) and $params['is_current_job']){
            $params['end_date'] = null;
        }
        return $this->repo->update($params, $id);
    }
}
