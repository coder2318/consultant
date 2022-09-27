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
}
