<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'profile_id',
        'text',
        'rating'
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
