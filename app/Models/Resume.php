<?php

namespace App\Models;

use App\Casts\ArrayStringCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resume extends BaseModel
{
    use HasFactory;

    const CREATED = 1; // yaratilgan
    const CONFIRMED = 2; // verifikatsiyadan o'tgan
    const BLOCKED = 3; // block qilingan

    protected $fillable = [
        'profile_id',
        'category_id',
        'language',
        'about',
        'files',
        'status',
        'visible',
        'skill_ids'
    ];

    protected $casts = [
        'language' => 'array',
        'skill_ids' => ArrayStringCast::class
    ];

    protected $appends = ['user'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if(auth()->user() && auth()->user()->profile)
                $model->profile_id = auth()->user()->profile->id;
        });

    }

    public function getUserAttribute(): array
    {
        $profile = Profile::find($this->profile_id);
        $user = User::find($profile->user_id);
        return ['fullname' => $user->l_name . ' '.$user->f_name, 'avatar' => $user->photo, 'last_online_at' => $profile->last_online_at];
    }
}
