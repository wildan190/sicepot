<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Force HTTPS for all generated URLs (route(), url(), asset(), etc.)
        // This prevents Mixed Content errors when the app is served over HTTPS
        // but APP_URL is still set to http://.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
