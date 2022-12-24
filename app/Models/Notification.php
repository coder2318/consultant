<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const TYPE_MESSAGE = 'message';
    const TYPE_RESUME = 'resume';
    const TYPE_APPLICATION = 'application';
    const TYPE_PRIVATE_APPLICATION = 'private_application';
    const TYPE_NEW_APPLICATIONS_COUNT = 'new_applications_count';
    protected $fillable = [
        'text',
        'description',
        'profile_id',
        'cascade',
        'showed',
        'type',
        'data',
        'link'
    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected static function booted()
    {
        static::addGlobalScope('unshowed', function (Builder $builder) {
            $builder->where('showed', false);
        });
    }
}
