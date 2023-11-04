<?php

namespace App\Providers;


use Config;
use Illuminate\Support\ServiceProvider;
use Str;
use URL;

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
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        URL::forceRootUrl(Config::get('app.url'));
        if (Str::contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
