<?php

namespace Liliom\OneSignal;

use Illuminate\Support\ServiceProvider;

class OneSignalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = dirname(__DIR__) . '/config/onesignal.php';

        if ( ! file_exists(config_path('onesignal.php'))) {
            $this->publishes([
                $configPath => config_path('onesignal.php')
            ], 'config');
        }

        if (class_exists('Laravel\Lumen\Application')) {
            $this->app->configure('onesignal');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/onesignal.php', 'onesignal');

        $this->app->singleton('onesignal', function ($app) {
            $config = isset($app['config']['services']['onesignal']) ? $app['config']['services']['onesignal'] : null;
            if (is_null($config)) {
                $config = $app['config']['onesignal'] ?: $app['config']['onesignal::config'];
            }

            $client = new OneSignalClient($config['app_id'], $config['rest_api_key'], $config['user_auth_key']);

            return $client;
        });
    }

    public function provides()
    {
        return ['onesignal'];
    }


}
