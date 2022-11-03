<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'description',
        'profile_id',
        'cascade',
        'showed',
        'type',
        'data'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('unshowed', function (Builder $builder) {
            $builder->where('showed', true);
        });
    }
}
