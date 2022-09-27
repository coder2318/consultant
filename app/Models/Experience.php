<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends BaseModel
{
    use HasFactory;

    protected $fillable =[
        'resume_id',
        'start_date',
        'end_date',
        'is_current_job',
        'company_name',
        'profession'
    ];
}
