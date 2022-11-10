<?php

namespace App\Services;

use App\Repositories\ResumeRepository;
use App\Traits\FilesUpload;

class ResumeService extends BaseService
{
    use FilesUpload;
    public function __construct(ResumeRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['category_id' => ['type' => 'integer'], 'status' => ['type' => 'integer']];
    }

    public function create($params): object
    {
        if(isset($params['files'])){
            $params = $this->fileUpload($params, 'applications');
        }
        return $this->repo->store($params);
    }

    public function edit($params, $id): mixed
    {
        if(isset($params['files'])){
            $resume = $this->repo->getById($id);
            $params = $this->fileUpload($params, 'applications', $resume);
        }
        return $this->repo->update($params, $id);
    }
}
