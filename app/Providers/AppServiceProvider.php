<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\FlyffConfigParserService;
use App\Service\FlyffInventoryParserService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FlyffConfigParserService::class, function ($app) {
            return new FlyffConfigParserService();
        });
       
        $this->app->singleton(FlyffInventoryParserService::class, function ($app) {
            return new FlyffInventoryParserService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \URL::forceScheme('https');
    }
}
