<?php

namespace App\Models;

use App\Models\Chat\Chat;
use Carbon\Carbon;
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

    protected $appends = ['user', 'category', 'chat_id'];

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(Application::class, 'application_id', 'id');
    }

    public function resume(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(Resume::class, 'resume_id', 'id');
    }

    public function getUserAttribute()
    {
        $resume = Resume::find($this->resume_id);
        if($resume){
            $profile = Profile::find($resume->profile_id);
            $user = User::find($profile->user_id);
            $is_online = false;
            if(Carbon::createFromDate($profile->last_online_at)->addMinutes(2)->gte(Carbon::now()))
                $is_online = true;
            return [
                'fullname' => $user->l_name . ' '.$user->f_name, 
                'avatar' => config('services.core_address').$user->photo,
                'id' => $user->id,
                'is_online' => $is_online
            ];
        }
        return null;
    }

    public function getCategoryAttribute()
    {
        $resume = Resume::find($this->resume_id);
        return $resume->category;
    }

    public function getChatIdAttribute()
    {
        $chat = Chat::where('application_id', $this->application_id)->where('profile_ids', '&&', '{'.auth()->user()->profile->id . '}')->first();
        if($chat)
            return $chat->id;
        return null;
    }
}
