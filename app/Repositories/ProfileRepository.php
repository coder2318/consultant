<?php

namespace App\Repositories;

use App\Models\Profile;

class ProfileRepository extends BaseRepository
{
    public function __construct(Profile $profile)
    {
        $this->entity = $profile;
    }
}
