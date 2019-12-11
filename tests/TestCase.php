<?php

namespace Ycs77\LaravelLineBot\Test;

use Mockery;
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
     * Mock an instance of an object in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|null  $mock
     * @return object
     */
    protected function mock($abstract, $mock = null)
    {
        return $this->app->instance($abstract, Mockery::mock(...array_filter(func_get_args())));
    }
}
