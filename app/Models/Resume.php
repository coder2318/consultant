<?php

namespace App\Models;

use App\Casts\ArrayStringCast;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resume extends BaseModel
{
    use HasFactory;

    const CREATED = 1; // yaratilgan
    const CONFIRMED = 2; // verifikatsiyadan o'tgan
    const BLOCKED = 3; // block qilingan

    protected $fillable = [
        'profile_id',
        'category_id',
        'language',
        'about',
        'files',
        'status',
        'visible',
        'skill_ids',
        'showed',
        'reason_inactive',
        'position'
    ];

    protected $casts = [
        'language' => 'array',
        'skill_ids' => ArrayStringCast::class
    ];

    protected $appends = ['user', 'review', 'category', 'skills', 'file_names', 'applications_count'];

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

            $builder->where('visible', true);

        });
    }

    public function getFilesAttribute($value)
    {
        if($value){
            $files = [];
            $arr = explode(',', $value);
            foreach ($arr as $item) {
                $files[] = config('app.url').'/'.$item;
            }
            return $files;
        }
        return null;
    }

    public function getFileNamesAttribute()
    {
        $files = $this->getRawOriginal('files');
        return explode(',', $files);
    }
    
    public function getUserAttribute(): array
    {
        $profile = Profile::find($this->profile_id);
        $user = User::find($profile->user_id);
        $is_online = false;
        if(Carbon::createFromDate($profile->last_online_at)->addMinutes(2)->gte(Carbon::now()))
            $is_online = true;
        return [
            'fullname' => $user->l_name . ' '.$user->f_name,
            'avatar' => $user->photo ? config('services.core_address').$user->photo : null,
            'last_online_at' => $profile->last_online_at,
            'id' => $user->id,
            'is_online' => $is_online
        ];
    }

    public function getReviewAttribute()
    {
        $reviews = Review::where('resume_id', $this->id)->get();
        $avg = array_filter($reviews->pluck('rating')->toArray());
        if(count($avg)){
            $average = array_sum($avg)/count($avg);
            return [
                'count' => $reviews->count(),
                'rating' => $average 
            ];
        }
        return [
            'count' => $reviews->count(),
            'rating' => 0 
        ];
    }

    public function getCategoryAttribute(): string
    {
        $category = Category::find($this->category_id);
        return $category->name;
    }

    public function getApplicationsCountAttribute()
    {
        return Application::where('resume_id', $this->id)->get()->count();
    }

    public function getSkillsAttribute()
    {
        $skills = Skill::whereIn('id',$this->skill_ids)->get()->pluck('name');
        return $skills;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'resume_id', 'id');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    public function scopeActive($query)
    {
        $query->whereIn('status', [self::CONFIRMED, self::CREATED]);
    }
}
