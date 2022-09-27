<?php

namespace App\Repositories;

use App\Models\Response;

class ResponseRepository extends BaseRepository
{
    public function __construct(Response $response)
    {
        $this->entity = $response;
    }
}
