<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'category_id',
        'sub_category_id',
        'language',
        'about',
        'files',
        'status'
    ];

    protected $casts = [
        'language' => 'array'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if(auth()->user() && auth()->user()->consultant)
                $model->profile_id = auth()->user()->consultant->id;
        });

    }
}
