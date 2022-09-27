<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'resume_id',
        'amount',
        'text',
        'status',
        'is_showed'
    ];
}
