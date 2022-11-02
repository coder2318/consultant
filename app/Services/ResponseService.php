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

    public function redirectToChat($response)
    {
        $resume = $this->resumeModel->where('id', $response->resume_id)->first();
        return $this->chatModel->where('application_id', $response->application_id)->where('profile_ids', '&&', '{'.auth()->user()->profile->id. ','.$resume->profile_id.'}')->first();
    }
}
