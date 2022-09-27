<?php

namespace App\Services;

use App\Repositories\ResponseRepository;

class ResponseService extends BaseService
{
    public function __construct(ResponseRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = [];
    }
}
