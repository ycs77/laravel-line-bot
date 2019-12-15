<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Ycs77\LaravelLineBot\Contracts\Response as ResponseContracts;

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
            return new LineBot(
                $app['linebot.base'],
                $app['linebot.cache'],
                $app['linebot.talks'],
                $app['config']['linebot']
            );
        });

        $this->app->singleton('linebot.base', function ($app) {
            return new BaseLINEBot($app['linebot.http'], [
                'channelSecret' => $app['config']['linebot.channel_secret'],
                'endpointBase' => $app['config']['linebot.endpoint_base'],
                'dataEndpointBase' => $app['config']['linebot.data_endpoint_base'],
            ]);
        });

        $this->app->singleton('linebot.cache', function ($app) {
            return $app['cache']->store($app['config']['linebot.cache']);
        });

        $this->app->singleton('linebot.http', function ($app) {
            return new CurlHTTPClient($app['config']['linebot.channel_access_token']);
        });

        $this->app->singleton('linebot.response', function ($app) {
            return new Response($app[ResponseFactory::class]);
        });

        $this->app->singleton('linebot.talks', function ($app) {
            return new TalkCollection();
        });

        // Set aliases
        $this->app->alias('linebot', LineBot::class);
        $this->app->alias('linebot.base', BaseLINEBot::class);
        $this->app->alias('linebot.http', HTTPClient::class);
        $this->app->alias('linebot.response', ResponseContracts::class);
        $this->app->alias('linebot.talks', TalkCollection::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/linebot.php', 'linebot');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishResources();
        $this->loadRoutes();
    }

    /**
     * Load the Line Bot routes.
     *
     * @return void
     */
    public function loadRoutes()
    {
        $routes_path = base_path(config('linebot.routes_path'));

        if (file_exists($routes_path)) {
            require $routes_path;
        }
    }

    /**
     * Publish the Line Bot resources.
     *
     * @return void
     */
    public function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/linebot.php' => config_path('linebot.php'),
            // __DIR__ . '/../routes/linebot.php' => base_path(config('linebot.routes_path')),
        ]);
    }
}
