<?php

namespace Bushuev\Mattermost\Providers;

use Bushuev\Mattermost\MattermostHook;
use Bushuev\Mattermost\Notifications\MattermostChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/mattermost.php' => config_path('mattermost.php'),
        ]);

        $this->app->bind('mattermost', function ($app) {
            return new MattermostHook();
        });

        // Register the notification channel
        $this->app->singleton(MattermostChannel::class, function ($app) {
            return new MattermostChannel($app['mattermost']);
        });

        Notification::extend('mattermost', function ($app) {
            return new MattermostChannel($app['mattermost']);
        });

    }
}
