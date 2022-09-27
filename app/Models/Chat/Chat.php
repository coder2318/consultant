<?php

namespace App\Models\Chat;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'profile_ids',
        'last_time'
    ];
}
