<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends BaseModel
{
    use HasFactory;

    const USER_ROLE = 'user';
    const CONSULTANT_ROLE = 'consultant';
    const ADMIN_ROLE = 'admin';

    protected $fillable = [
        'user_id',
        'role',
        'is_active'
    ];
}
