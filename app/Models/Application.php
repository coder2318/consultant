<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resume_id',
        'profile_id',
        'category_id',
        'date',
        'text',
        'status',
        'files'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if(auth()->user() && auth()->user()->profile)
                $model->profile_id = auth()->user()->profile->id;
        });

    }
}
