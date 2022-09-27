<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends BaseModel
{
    use HasFactory;

    const WAITING_STATUS = 1;
    const SUCCESS_STATUS = 2;
    const DENIED_STATUS = 3;

    protected $fillable = [
        'application_id',
        'profile_id',
        'amount',
        'payment_type',
        'status'
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
