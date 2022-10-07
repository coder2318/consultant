<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resume extends BaseModel
{
    use HasFactory;

    const CREATED = 1;
    const CHECKED = 2;
    const VISIBLE = 3;
    const HIDDEN = 4;

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
            if(auth()->user() && auth()->user()->profile)
                $model->profile_id = auth()->user()->profile->id;
        });

    }
}
