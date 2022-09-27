<?php

namespace App\Repositories;

use App\Models\Application;

class ApplicationRepository extends BaseRepository
{
    public function __construct(Application $application)
    {
        $this->entity = $application;
    }
}
