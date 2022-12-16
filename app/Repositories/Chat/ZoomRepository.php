<?php


namespace App\Repositories\Chat;


use App\Models\Chat\Zoom;
use App\Repositories\BaseRepository;

class ZoomRepository extends BaseRepository
{
    public function __construct(Zoom $zoom)
    {
        $this->entity = $zoom;
    }
}