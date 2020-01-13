<?php

namespace Ycs77\LaravelLineBot\Commands\Concerns;

trait CommandHelper
{
    /**
     * Check the application is Lumen.
     *
     * @return bool
     */
    public function isLumen()
    {
        return $this->laravel instanceof \Laravel\Lumen\Application;
    }
}
