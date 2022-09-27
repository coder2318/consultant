<?php

namespace App\Services;

use App\Repositories\CommentRepository;

class CommentService extends BaseService
{
    public function __construct(CommentRepository $repository)
    {
        $this->repo = $repository;
    }

    public function create($params): object
    {
        return $this->repo->store($params);
    }
}
