<?php

namespace App\Models;

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

    protected $appends = ['user'];

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
            $user_id = Profile::find($resume->profile_id)->user_id;
            $user = User::find($user_id);
            return [
                'fullname' => $user->l_name . ' '.$user->f_name, 
                'avatar' => $user->photo
            ];
        }
    }
}
