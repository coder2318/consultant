<?php


namespace App\Repositories;

use App\Models\Review;

class ReviewRepository extends BaseRepository
{
    public function __construct(Review $review)
    {
        $this->entity = $review;
    }
}