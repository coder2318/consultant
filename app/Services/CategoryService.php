<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Resume;
use App\Repositories\CategoryRepository;
use App\Traits\FilesUpload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryService extends BaseService
{
    use FilesUpload;

    public function __construct(CategoryRepository $repository, protected Resume $resumeModel)
    {
        $this->repo = $repository;
        $this->filter_fields = ['name' => ['type' => 'string'], 'id' => ['type' => 'number']];
    }

    public function checkList(): array|\Illuminate\Support\Collection
    {
        $profile = auth()->user()->profile ?? null;
        if ($profile) {
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
        return $result->map(function ($item) {
            $item->disabled = false;
            return $item;
        });
    }

    public function create($params): object
    {
        $params = $this->fileUpload($params, 'categories');
        $params['name'] = json_decode($params['name']);
        return $this->repo->store($params);
    }

    public function edit($params, $id): mixed
    {
        $category = $this->repo->getById($id);
        $params = $this->fileUpload($params, 'categories', $category);
        return $this->repo->update($params, $id);
    }

    public function getSelfCategory(): array|Collection
    {
        $resumes = $this->resumeModel->where('profile_id', auth()->user()->profile->id)->get();
        if($resumes){
            return $this->repo->getQuery()->whereIn('id', $resumes->pluck('category_id'))->get();
        }
        return [];
    }

    public function topCategories()
    {
        $category_ids = DB::table('applications')
            ->select(
                'category_id',
                DB::raw("count(id) as count")
            )->groupBy('category_id')
            ->orderBy('count', 'desc')
            ->limit(4)->get()->pluck('category_id');

        return $this->repo->getQuery()->whereIn('id', $category_ids)->get();
    }
}
