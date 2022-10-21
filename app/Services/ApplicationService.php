<?php

namespace App\Services;

use App\Repositories\ApplicationRepository;
use App\Traits\FilesUpload;

class ApplicationService extends BaseService
{
    use FilesUpload;
    public function __construct(ApplicationRepository $repo)
    {
        $this->repo = $repo;
        $this->filter_fields = ['resume_id' => ['type' => 'number'], 'application_id' => ['type' => 'number']];
        $this->attributes = [
            'id', 'description', 'status', 'files', 'created_at', 'type', 'price_from', 'price_to', 'title', 'profile_id', 'category_id'
        ];
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
        $model->update([
            'views' => (int) $model->views + 1
        ]);
        return $model;
    }
}
