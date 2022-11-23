<?php

namespace App\Services;

use App\Repositories\ResumeRepository;
use App\Traits\FilesUpload;

class ResumeService extends BaseService
{
    use FilesUpload;
    public function __construct(ResumeRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['category_id' => ['type' => 'number'], 'status' => ['type' => 'number']];
    }

    public function myIndex()
    {
        $query = $this->repo->getQuery();
        $query = $this->select($query, $this->attributes);
        $query = $query->where('profile_id', auth()->user()->profile->id);
        if(isset($params['limit']))
            $query = $query->limit($params['limit']);

        return $query->get();
    }

    public function checkHasResume()
    {
        $resumes = $this->repo->getQuery()->where('profile_id', auth()->user()->profile->id)->get()->count();
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
}
