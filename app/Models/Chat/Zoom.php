<?php

namespace App\Models\Chat;

use App\Casts\ArrayStringCast;
use App\Models\BaseModel;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zoom extends BaseModel
{
    use HasFactory;

    const MISSED = 1;
    const INCOMING = 2;
    const DECLINED = 3;

    protected $fillable = [
        'chat_id',
        'profile_ids',
        'end_time',
        'status',
        'from_profile_id'
    ];
    protected $appends = ['to_profile_id', 'profile'];

    protected $casts = [
        'profile_ids' => ArrayStringCast::class
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if(auth()->user() && auth()->user()->profile)
                $model->from_profile_id = auth()->user()->profile->id;
        });
    }

    public function getToProfileIdAttribute()
    {
        $currentUserId = auth()->check() ? auth()->user()->profile->id : auth()->id();

        $userArray = explode(',', str_replace(['{','}'], '',$this->attributes['profile_ids']));
        $userArray = array_map('intval',$userArray);

        $userID = current($userArray);

        if($userID == $currentUserId){
            $userID = next($userArray);
        }

        return $userID;
    }
    public function getProfileAttribute()
    {
        if(auth()->check()){
            $user_id = Profile::find($this->to_profile_id)->user_id;
            $user = User::find($user_id);
            return [
                'profile_id' => $this->to_profile_id,
                'fullname' => $user->l_name . ' '.$user->f_name,
                'avatar' => config('services.core_address').$user->photo
            ];
        }
        return null;
    }
}
