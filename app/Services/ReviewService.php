<?php


namespace App\Services;

use App\Repositories\ReviewRepository;

class ReviewService extends BaseService
{
    public function __construct(ReviewRepository $repository)
    {
        $this->repo = $repository;
        $this->filter_fields = ['resume_id' => ['type' => 'number']];
    }
}