<?php


namespace App\Repositories;


use App\Models\Skill;

class SkillRepository extends BaseRepository
{
    public function __construct(Skill $skill)
    {
        $this->entity = $skill;
    }
}