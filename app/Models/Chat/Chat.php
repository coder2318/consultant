<?php

namespace App\Models\Chat;

use App\Casts\ArrayStringCast;
use App\Models\BaseModel;
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

    protected $appends = ['to_profile_id', 'unread_count'];

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

    public function getUnreadCountAttribute($value)
    {
        return $this->messages()->unread()->count();
    }

    public function messages()
    {
        return $this->hasOne(ChatMessage::class);
    }

}
