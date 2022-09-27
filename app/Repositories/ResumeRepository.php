<?php

namespace App\Repositories;

use App\Models\Resume;

class ResumeRepository extends BaseRepository
{
    public function __construct(Resume $resume)
    {
        $this->entity = $resume;
    }
}
