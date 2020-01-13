<?php

namespace Ycs77\LaravelLineBot;

use Laravel\Lumen\Http\ResponseFactory;

class LineBotLumenServiceProvider extends LineBotServiceProvider
{
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
        return base_path('config/linebot.php');
    }
}
