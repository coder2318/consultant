<?php

namespace App\Models\Chat;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * App\Models\Chatapp\ChatMessage
 *
 * @property int $id
 * @property int $chat_id
 * @property int $from_user_id
 * @property string $message
 * @property bool $is_showed
 * @property string|null $showed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat\Chat|null $chat
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage notShowed()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereIsShowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereShowedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ChatMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatMessage extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'from_profile_id',
        'message',
        'is_showed',
        'showed_at',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            $model->chat()->update([
                'last_time' => $model->created_at
            ]);
        });
    }

    public function chat(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Chat::class,'id', 'chat_id');
    }

    public function scopenotShowed($query)
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
}
