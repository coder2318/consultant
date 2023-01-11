<?php

namespace App\Services;

use App\Repositories\ResumeRepository;
use App\Traits\FilesUpload;
use Illuminate\Database\Eloquent\Builder;

class ResumeService extends BaseService
{
    use FilesUpload;
    public function __construct(ResumeRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['category_id' => ['type' => 'number'], 'status' => ['type' => 'number']];
    }

    public function get(array $params, $pagination = true)
    {
        $perPage = null;
        if ($pagination) {
            $perPage = isset($params['per_page']) ? $params['per_page'] : 20;
        }

        $query = $this->repo->getQuery();
        $query = $this->relation($query, $this->relation);
        $query = $this->filter($query, $this->filter_fields, $params);
        if(isset($params['search']))
        {
            $query =
                $query->where(function ($q) use ($params){
                   $q->where('position', 'ilike', '%'.$params['search'].'%')
                       ->orWhereHas('profile', function (Builder $builder) use ($params) {
                           $builder->whereHas('user', function (Builder $b) use ($params){
                                $b->where('f_name', 'ilike', '%'.$params['search'].'%')
                                    ->orWhere('l_name', 'ilike', '%'.$params['search'].'%');
                           });
                       });
                });
        }
        $query = $this->sort($query, $this->sort_fields, $params);
        $query = $this->select($query, $this->attributes);
        $query = $this->repo->getPaginate($query, $perPage);
        return $query;
    }

    public function myIndex()
    {
        $query = $this->repo->getQuery();
        $query = $this->select($query, $this->attributes);
        $query = $query->where('profile_id', auth()->user()->profile->id);
        $query = $query->withoutGlobalScope('visible');
        if(isset($params['limit']))
            $query = $query->limit($params['limit']);

        return $query->get();
    }

    public function checkHasResume()
    {
        $resumes = $this->repo->getQuery()->where('profile_id', auth()->user()->profile->id)->withoutGlobalScope('visible')->get()->count();
        if($resumes)
            return true;
        return false;
    }

    public function create($params): object
    {
        if(isset($params['files'])){
            $params = $this->fileUpload($params, 'applications');
        }
        return $this->repo->store($params);
    }

    public function edit($params, $id): mixed
    {
        $resume = $this->repo->getById($id);
        $this->fileDelete($params, $resume);
        if(isset($params['files'])){
            $params = $this->fileUpload($params, 'resumes', $resume);
        }

        return $this->repo->update($params, $id);
    }

    public function fileDelete($params, $resume)
    {
        if(isset($params['file_delete']) && count($params['file_delete'])) {
            $fileNames = array_diff($resume->file_names, $params['file_delete']);
            foreach ($params['file_delete'] as $item) {
                unlink($item);
            }
            $fileString = implode(',', $fileNames);
            $params['files'] = $fileString;
            $resume->files = $params['files'];
            $resume->save();
        }
    }

    public function topConsultant($params)
    {
        $limit = $params['limit'] ?? 4;
        $resumes = $this->repo->getQuery()->limit($limit)->get();
        return $resumes->sortBy(function ($model){
            return $model->review['rating'];
        })->toArray();

    }
}
