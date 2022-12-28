<?php

namespace App\Providers;

use App\Mixins\ResponseFactoryMixin;
use App\Models\Application;
use App\Models\Category;
use App\Models\Chat\Zoom;
use App\Models\Profile;
use App\Models\Response;
use App\Models\Resume;
use App\Observers\ApplicationObserver;
use App\Observers\CategoryObserver;
use App\Observers\ProfileObserver;
use App\Observers\ResponseObserver;
use App\Observers\ResumeObserver;
use App\Observers\ZoomObserver;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\URL;
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
        if(config('app.env' != 'local')) {
            info('success env');
            URL::forceScheme('https');
            URL::forceRootUrl(env('APP_URL'));
        }
        ResponseFactory::mixin(new ResponseFactoryMixin());
        Application::observe(ApplicationObserver::class);
        Resume::observe(ResumeObserver::class);
        Profile::observe(ProfileObserver::class);
        Category::observe(CategoryObserver::class);
        Zoom::observe(ZoomObserver::class);
        Response::observe(ResponseObserver::class);
    }
}
