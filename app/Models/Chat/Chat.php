<?php

namespace App\Models\Chat;

use App\Casts\ArrayStringCast;
use App\Models\Application;
use App\Models\BaseModel;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'profile_ids',
        'last_time'
    ];

    protected $casts = [
        'profile_ids' => ArrayStringCast::class
    ];

    protected $appends = ['to_profile_id', 'unread_count', 'profile'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->last_time = Carbon::now()->toDateTimeString();
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
                'avatar' => config('services.core_address').$user->photo,
                'id' => $user->id
            ];
        }
        return null;
    }

    public function getUnreadCountAttribute($value)
    {
        return $this->messages()->unread()->count();
    }

    public function messages()
    {
        return $this->hasOne(ChatMessage::class)->orderBy('created_at', 'desc');
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

}
