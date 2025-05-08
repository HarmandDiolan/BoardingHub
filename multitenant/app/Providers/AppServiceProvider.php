<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Events\TenancyInitialized;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Tenant\ThemeController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(TenancyInitialized::class, function($event){
            if(tenant()?->disabled){
                abort(403, 'This tenant is currently disabeld.');
            }
        });

        // Share theme settings with all views
        View::composer('*', function ($view) {
            $themeController = new ThemeController();
            $view->with('theme', $themeController->getThemeSettings());
        });
    }
}
