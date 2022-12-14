<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'is_main'
    ];

    protected $appends = ['category'];

    public function getCategoryAttribute(): string
    {
        $category = Category::find($this->category_id);
        return $category->name;
    }
}
