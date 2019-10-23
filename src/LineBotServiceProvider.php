<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Support\ServiceProvider;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LineBotServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('linebot', function ($app) {
            return new LineBot($app['linebot.base']);
        });

        $this->app->singleton('linebot.base', function ($app) {
            return new BaseLINEBot($app['linebot.http'], [
                'channelSecret' => $app['config']->get('channel_secret'),
                'endpointBase' => $app['config']->get('endpoint_base'),
            ]);
        });

        $this->app->singleton('linebot.http', function ($app) {
            return new CurlHTTPClient($app['config']->get('channel_access_token'));
        });

        $this->mergeConfigFrom(__DIR__ . '/../config/linebot.php', 'linebot');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/linebot.php' => config_path('linebot.php'),
        ], 'linebot');
    }
}
