<?php


namespace App\Services;


use App\Repositories\SkillRepository;

class SkillService extends BaseService
{
    public function __construct(SkillRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['name' => ['type' => 'string'], 'category_id' => ['type' => 'number'], 'is_main' => ['type' => 'bool']];
    }
}