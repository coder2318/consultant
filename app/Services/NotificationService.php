<?php


namespace App\Services;


use App\Repositories\NotificationRepository;

class NotificationService extends BaseService
{
    public function __construct(NotificationRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['profile_id' => ['type' => 'number'] ];
    }

    public function showNew($id)
    {
        $model = $this->repo->getById($id);
        $model->update([
            'showed' => true
        ]);
        return $model;
    }

    public function makeShowedAll()
    {
        $this->repo->getQuery()->where('profile_id', auth()->user()->profile->id)->update([
            'showed' => true
        ]);
        return true;

    }
}