<?php

namespace App\Services;

use App\Repositories\CategoryRepository;

class CategoryService extends BaseService
{
    public function __construct(CategoryRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['name' => ['type' => 'string']];
    }
}
