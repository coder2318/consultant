<?php

namespace App\Services;

use App\Repositories\PaymentRepository;

class PaymentService extends BaseService
{
    public function __construct(PaymentRepository $repository)
    {
        $this->repo =$repository;
        $this->filter_fields = ['status' => ['type' => 'number']];
    }

    public function get(array $params, $pagination = true)
    {
        $perPage = null;
        if ($pagination) {
            $perPage = isset($params['per_page']) ? $params['per_page'] : 20;
        }

        $query = $this->repo->getQuery();
        $query = $query->where('profile_id', auth()->user()->profile->id);
        $query = $this->relation($query, $this->relation);
        $query = $this->filter($query, $this->filter_fields, $params);
        $query = $this->sort($query, $this->sort_fields, $params);
        $query = $this->select($query, $this->attributes);
        $query = $this->repo->getPaginate($query, $perPage);
        return $query;
    }
}
