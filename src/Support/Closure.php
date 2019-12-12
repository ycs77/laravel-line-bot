<?php

namespace Ycs77\LaravelLineBot\Support;

/**
* Class Closure
*
* @see \Closure
*/
class Closure
{
    /**
     * @param  callable  $callable
     * @return \Closure
     * @see \Closure::fromCallable()
     */
    public static function fromCallable(callable $callable)
    {
        // In case we've got it native, let's use that native one!
        if(method_exists(\Closure::class, 'fromCallable')) {
            return \Closure::fromCallable($callable);
        }

        return function () use ($callable) {
            return call_user_func_array($callable, func_get_args());
        };
    }
}
