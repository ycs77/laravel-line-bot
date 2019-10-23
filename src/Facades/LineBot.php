<?php

namespace Ycs77\LaravelLineBot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LINE\LINEBot
 */
class LineBot extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'linebot';
    }
}
