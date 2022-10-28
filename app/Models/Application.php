<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends BaseModel
{
    use HasFactory, SoftDeletes;

    /** @var int application holati yani elon qilingan, consultant bn kelishilgan, chernovik qilib qoyilgan, tugatilgan */
    const PUBLISHED = 1; // e'lon qilingan
    const CONFIRMED = 2; //consultant bn kelishilgan
    const DRAFTED = 3; //chernovik qilib qoyilgan
    const FINISHED = 4; //tugatilgan
    const INACTIVE = 5; //deactivatsiya qilingan

    /** @var string application turi yani hammaga yoki aynan bitta consultantga  */
    const PUBLIC = 'public';
    const PRIVATE = 'private';

    protected $fillable = [
        'resume_id',
        'title',
        'profile_id',
        'category_id',
        'description',
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
        'payment_verified',
        'showed',
        'reason_inactive'
    ];

    protected $appends = ['user', 'category'];
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

    public function profile(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function getUserAttribute(): string
    {
        $user_id = Profile::find($this->profile_id)->user_id;
        $user = User::find($user_id);
        return $user->l_name . ' '.$user->f_name;
    }

    public function getCategoryAttribute(): string
    {
        $category = Category::find($this->category_id);
        $lang = request()->header('Language');
        return $category->name[$lang]??'';
    }
}
