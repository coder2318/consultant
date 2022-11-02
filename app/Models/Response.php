<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends BaseModel
{
    use HasFactory;

    const SEND = 1;
    const ACCEPT = 2;
    const DENY = 3;

    protected $fillable = [
        'application_id',
        'resume_id',
        'amount',
        'text',
        'status',
        'is_showed'
    ];

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(Application::class, 'application_id', 'id');
    }
}
