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
        'icon',
        'file'
    ];

    protected $casts = [
        'name' => 'array'
    ];

    public function getIconAttribute($value)
    {
        if($value)
            return config('app.url').'/'.$value;
        return null;
    }

    public function getFileAttribute($value)
    {
        if($value)
            return config('app.url').'/'.$value;
        return null;
    }

    public function getNameAttribute($value): string
    {
        $lang = request()->header('Language');
        $name = json_decode($value, true);
        return $name[$lang]??$name['ru'];
    }
}
