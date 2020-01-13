<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use Ycs77\LaravelLineBot\Contracts\Response as ResponseContract;

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
                $app['config'],
                $app['cache.store']
            );
        });

        $this->app->singleton('linebot.base', function ($app) {
            return new BaseLINEBot($app['linebot.http'], [
                'channelSecret' => $app['config']['linebot.channel_secret'],
                'endpointBase' => $app['config']['linebot.endpoint_base'],
                'dataEndpointBase' => $app['config']['linebot.data_endpoint_base'],
            ]);
        });

        $this->app->singleton('linebot.http', function ($app) {
            return new CurlHTTPClient($app['config']['linebot.channel_access_token']);
        });

        $this->app->singleton('linebot.response', function ($app) {
            return new Response($this->getResponseFactory($app));
        });

        $this->setAliases([
            'linebot'          => [LineBot::class],
            'linebot.base'     => [BaseLINEBot::class],
            'linebot.http'     => [CurlHTTPClient::class, HTTPClient::class],
            'linebot.response' => [Response::class, ResponseContract::class],
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/linebot.php', 'linebot');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->publishResources();
    }

    /**
     * Set instances aliases.
     *
     * @param  array  $aliasMap
     * @return void
     */
    protected function setAliases(array $aliasMap)
    {
        foreach ($aliasMap as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Get the response factory instance.
     *
     * @param  mixed  $app
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Laravel\Lumen\Http\ResponseFactory
     */
    protected function getResponseFactory($app)
    {
        return $app[ResponseFactory::class];
    }

    /**
     * Get the config file path.
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return config_path('linebot.php');
    }

    /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            Commands\InstallCommand::class,
            Commands\CreateLineBotRichMenuCommand::class,
            Commands\GetLineBotRichMenuListCommand::class,
            Commands\ClearLineBotRichMenuCommand::class,
        ]);
    }

    /**
     * Publish the Line Bot resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../config/linebot.php' => $this->getConfigPath(),
        ]);
    }
}
