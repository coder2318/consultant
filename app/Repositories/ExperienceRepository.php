<?php

namespace App\Repositories;

use App\Models\Experience;

class ExperienceRepository extends BaseRepository
{
    public function __construct(Experience $experience)
    {
        $this->entity = $experience;
    }
}
