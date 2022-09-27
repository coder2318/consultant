<?php

namespace App\Services;

use App\Repositories\ReactionRepository;

class ReactionService extends BaseService
{
    public function __construct(ReactionRepository $repository)
    {
        $this->repo = $repository;
    }
}
