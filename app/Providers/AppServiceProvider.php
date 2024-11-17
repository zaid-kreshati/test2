<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\services\PassportService;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        // Bind PassportService into the container
        $this->app->singleton(PassportService::class, function ($app) {
            return new PassportService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
