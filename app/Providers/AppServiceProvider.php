<?php

namespace App\Providers;

use App\Mixins\ResponseFactoryMixin;
use App\Models\Application;
use App\Models\Profile;
use App\Models\Resume;
use App\Observers\ApplicationObserver;
use App\Observers\ProfileObserver;
use App\Observers\ResumeObserver;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResponseFactory::mixin(new ResponseFactoryMixin());
        Application::observe(ApplicationObserver::class);
        Resume::observe(ResumeObserver::class);
        Profile::observe(ProfileObserver::class);
    }
}
