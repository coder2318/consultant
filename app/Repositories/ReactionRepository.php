<?php

namespace App\Repositories;

use App\Models\Reaction;

class ReactionRepository extends BaseRepository
{
    public function __construct(Reaction $reaction)
    {
        $this->entity = $reaction;
    }
}
