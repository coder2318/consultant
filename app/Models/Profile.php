<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends BaseModel
{
    use HasFactory;

    const USER_ROLE = 'user';
    const ADMIN_ROLE = 'admin';

    protected $fillable = [
        'user_id',
        'role',
        'is_active',
        'is_consultant',
        'last_online_at'
    ];

    protected $casts = [
        "last_online_at" => "datetime"
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
