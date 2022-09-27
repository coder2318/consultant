<?php

namespace App\Services;

use App\Repositories\ProfileRepository;

class ProfileService extends BaseService
{
    public function __construct(ProfileRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['role' => ['type' => 'string']];
    }

}
