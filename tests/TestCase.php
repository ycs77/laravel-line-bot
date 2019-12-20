<?php

namespace Ycs77\LaravelLineBot\Test;

use Closure;
use Mockery as m;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Ycs77\LaravelLineBot\LineBotServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            LineBotServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('linebot.channel_access_token', 'Foo');
        $app['config']->set('linebot.channel_secret', 'Bar');
        $app['config']->set('linebot.endpoint_base', 'https://api.line.me');
        $app['config']->set('linebot.data_endpoint_base', 'https://api-data.line.me');
    }

    /**
     * Mock an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return \Mockery\MockInterface
     */
    protected function mock($abstract, Closure $mock = null)
    {
        return $this->app->instance($abstract, m::mock(...array_filter(func_get_args())));
    }

    /**
     * Mock a partial instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return \Mockery\MockInterface
     */
    protected function partialMock($abstract, Closure $mock = null)
    {
        return $this->app->instance($abstract, m::mock(...array_filter(func_get_args()))->makePartial());
    }
}
