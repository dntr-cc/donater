<?php

namespace App\Providers;

use App\Services\GoogleServiceSheets;
use Config;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Str;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GoogleServiceSheets::class, function () {
            $client = new Google_Client();
            $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
            $client->setAccessType('offline');
            $client->setAuthConfig(base_path('google.json'));

            return new GoogleServiceSheets(new Google_Service_Sheets($client));
        });
    }

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
