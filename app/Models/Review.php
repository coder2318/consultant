<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'resume_id',
        'profile_id',
        'text',
        'rating'
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
        return ['fullname' => $user->l_name . ' '.$user->f_name, 'avatar' => config('services.core_address').$user->photo, 'last_online_at' => $profile->last_online_at];
    }
}
