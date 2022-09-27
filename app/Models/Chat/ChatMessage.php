<?php

namespace App\Models\Chat;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'from_profile_id',
        'message',
        'is_showed',
        'showed_at'
    ];
}
