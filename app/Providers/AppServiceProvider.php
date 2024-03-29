<?php

namespace App\Providers;

use App\Events\OpenGraphRegenerateEvent;
use App\Listeners\OpenGraphRegenerate;
use App\Services\GoogleServiceSheets;
use Config;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
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
        Paginator::useBootstrapFive();
        URL::forceRootUrl(Config::get('app.url'));
        if (Str::contains(Config::get('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
        Event::listen(
            OpenGraphRegenerateEvent::class,
            OpenGraphRegenerate::class,
        );
        if (auth()?->user()?->getId() === 1) {
            dd(auth());
            config()->set('debugbar.enabled', true);
        }
    }
}
