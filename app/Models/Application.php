<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends BaseModel
{
    use HasFactory, SoftDeletes;

    const TODAY = 'today';
    const TOMORROW = 'tomorrow';
    const IN_WEEK = 'in_week';
    const WHENEVER = 'whenever';

    /** @var int application holati yani elon qilingan, consultant bn kelishilgan, chernovik qilib qoyilgan, tugatilgan */
    const PUBLISHED = 1;
    const CONFIRMED = 2;
    const DRAFTED = 3;
    const FINISHED = 4;

    /** @var string application turi yani hammaga yoki aynan bitta consultantga  */
    const PUBLIC = 'public';
    const PRIVATE = 'private';

    protected $fillable = [
        'resume_id',
        'name',
        'profile_id',
        'category_id',
        'text',
        'status',
        'files',
        'views',
        'type',
        'is_visible',
        'expired_date',
        'price_from',
        'price_to',
        'when',
        'when_date',
        'payment_verified'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            if(auth()->user() && auth()->user()->profile)
                $model->profile_id = auth()->user()->profile->id;
        });

    }

    public function getFilesAttribute($value)
    {
        if($value){
            $files = [];
            $arr = explode(',', $value);
            foreach ($arr as $item) {
                $files[] = url('/').'/'.$item;
            }
            return $files;
        }
        return null;
    }
}
