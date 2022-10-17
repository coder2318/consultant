<?php

namespace App\Services;

use App\Models\Resume;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\DB;

class CategoryService extends BaseService
{
    public function __construct(CategoryRepository $repository, protected Resume $resumeModel)
    {
        $this->repo = $repository;
        $this->filter_fields = ['name' => ['type' => 'string']];
    }

    public function checkList(): array|\Illuminate\Support\Collection
    {
        $profile = auth()->user()->profile??null;
        if($profile){
            return DB::table('categories')
                ->leftJoin('resumes', 'categories.id', '=', 'resumes.category_id')
                ->select(
                    'categories.id as id', 'categories.name as name',
                    DB::raw("(case when (categories.id = resumes.category_id and resumes.profile_id = $profile->id) then true else false end) as disabled")
                )->get();
        }
        $result = DB::table('categories')
            ->select(
                'categories.id as id', 'categories.name as name'
            )->get();
        return $result->map(function ($item){
            $item->disabled = false;
            return $item;
        });
    }
}
