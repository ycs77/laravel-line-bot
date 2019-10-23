<?php

namespace Ycs77\LaravelLineBot;

use LINE\LINEBot as BaseLINEBot;

/**
 * @mixen \LINE\LINEBot
 */
class LineBot
{
    /**
     * The base Line Bot client instance.
     *
     * @var \LINE\LINEBot
     */
    protected $lineBot;

    /**
     * Create a new Line Bot client instance.
     *
     * @return void
     */
    public function __construct(BaseLINEBot $lineBot)
    {
        $this->lineBot = $lineBot;
    }

    /**
     * Call method from base Line Bot client.
     *
     * @param  string  $name
     * @param  array  $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return $this->lineBot->{$name}(...$arguments);
    }
}
