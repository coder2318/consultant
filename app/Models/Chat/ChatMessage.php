<?php

namespace App\Models\Chat;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends BaseModel
{
    use HasFactory;

    const OFFER = 1;
    const ACCEPT = 2;
    const WAIT = 3;

    const TYPE_CHAT = 'chat';
    const TYPE_FILE = 'file';
    const TYPE_CALL = 'call';

    const ZOOM_MISSED = 1;
    const ZOOM_INCOMING = 2;
    const ZOOM_DECLINED = 3;

    protected $fillable = [
        'chat_id',
        'from_profile_id',
        'message',
        'is_showed',
        'showed_at',
        'created_at',
        'updated_at',
        'is_price',
        'file',
        'file_original_name',
        'action_status', // clientlar  uchun podskazka beradigan status,
        'type', // messagelarni type file, chat, call bulishi mumkin
        'call_status', // incoming, missed, canceled bulishi mumkin
        'zoom_id'
    ];

    protected $appends = ['owner'];

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->chat()->update([
                'last_time' => $model->created_at
            ]);
        });
    }

    public function getFileAttribute($value)
    {
        if($value)
            return config('app.url').'/'.$value;
        return null;
    }

    public function chat(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Chat::class,'id', 'chat_id');
    }

    public function scopeNotShowed($query)
    {
        return $query->where('is_showed', false);
    }

    public function scopeUnread($query)
    {
        return $query->where([
            ['is_showed', false],
            ['from_profile_id', '!=',  auth()->check() ? auth()->user()->profile->id : auth()->id()]
        ]);
    }

    public function getOwnerAttribute()
    {
        if(auth()->check()){
            if(auth()->user()->profile->id == $this->from_profile_id)
                return true;
            return false;
        }
        return true;
    }
}
