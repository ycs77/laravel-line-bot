<?php

namespace Ycs77\LaravelLineBot\Matching;

class TextMessageParameters
{
    /**
     * Get the parameters.
     *
     * @param  array  $matches
     * @param  array  $names
     * @return array
     */
    public static function get(array $matches, array $names)
    {
        return array_values(array_intersect_key($matches, array_flip($names)));
    }

    /**
     * Get the parameter names for the route.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function compileNames($value)
    {
        preg_match_all(Matcher::PATTERN, $value, $matches);

        return array_map(function ($m) {
            return trim($m, '?');
        }, $matches[1]);
    }
}
