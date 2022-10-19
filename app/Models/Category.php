<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'icon'
    ];

    protected $casts = [
        'name' => 'array'
    ];

    public function getIconAttribute($value)
    {
        return url('/').'/'.$value;
    }
}
