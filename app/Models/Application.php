<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends BaseModel
{
    use HasFactory, SoftDeletes;

    /** @var int application holati yani elon qilingan, consultant bn kelishilgan, chernovik qilib qoyilgan, tugatilgan -> status*/
    const PUBLISHED = 1; // e'lon qilingan
    const CONFIRMED = 2; //consultant bn kelishilgan
    const DRAFTED = 3; //chernovik qilib qoyilgan
    const FINISHED = 4; //tugatilgan
    const INACTIVE = 5; //deactivatsiya qilingan
    const CANCELED = 6; //otmen qilingan

    /** @var string application turi yani hammaga yoki aynan bitta consultantga -> type */
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

    protected $appends = ['user', 'category', 'response_count', 'response_status'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if(auth()->user() && auth()->user()->profile)
                $model->profile_id = auth()->user()->profile->id;
        });
    }

    protected static function booted()
    {
        static::addGlobalScope('visible', function (Builder $builder) {

            $builder->where('is_visible', true);

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

    public function response(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->HasMany(Response::class, 'application_id', 'id');
    }

    public function getUserAttribute()
    {
        $profile = Profile::find($this->profile_id);
        $user = User::find($profile->user_id);
        return ['fullname' => $user->l_name . ' '.$user->f_name, 'avatar' => config('services.core_address').$user->photo, 'last_online_at' => $profile->last_online_at];

    }

    public function getCategoryAttribute(): string
    {
        $category = Category::find($this->category_id);
        return $category->name;
    }

    public function getResponseCountAttribute()
    {
        return Response::where('application_id', $this->id)->get()->count();
    }

    public function scopePublish($query)
    {
        $query->where('status', self::PUBLISHED);
    }

    public function scopeConfirm($query)
    {
        $query->whereIn('status', [self::CONFIRMED, self::FINISHED, self::CANCELED]);
    }

    public function getResponseStatusAttribute()
    {
        if(auth()->check()){
            $resume_ids = Resume::where('profile_id', auth()->user()->profile->id)->get()->pluck('id');
            $response = Response::where('application_id', $this->id)->whereIn('resume_id', $resume_ids)->first();
            return $response ? $response->status : null;
        }
        return null;
    }


}
