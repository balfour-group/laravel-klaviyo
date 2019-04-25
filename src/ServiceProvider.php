<?php

namespace Balfour\LaravelKlaviyo;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config.php' => config_path('klaviyo.php')], 'config');
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config.php', 'klaviyo');

        $this->app->bind(Klaviyo::class, function () {
            $client = new Client();
            return new Klaviyo(
                $client,
                config('klaviyo.api_key')
            );
        });
    }
}
