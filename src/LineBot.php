<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Cache\Repository as Cache;
use LINE\LINEBot as BaseLINEBot;

/**
 * @mixen \LINE\LINEBot
 */
class LineBot
{
    /**
     * The base Line Bot SDK instance.
     *
     * @var \LINE\LINEBot
     */
    protected $bot;

    /**
     * The cache repository interface.
     *
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;

    /**
     * The Line Bot config.
     *
     * @var array
     */
    protected $config;

    /**
     * Create a new Line Bot SDK instance.
     *
     * @param  \LINE\LINEBot  $bot
     * @param  \Illuminate\Cache\Repository  $cache
     * @param  \Ycs77\LaravelLineBot\Response  $response
     * @param  array  $config
     * @return void
     */
    public function __construct(BaseLINEBot $bot, Cache $cache, array $config = [])
    {
        $this->bot = $bot;
        $this->cache = $cache;
        $this->config = $config;
    }

    /**
     * Get base Line Bot SDK instance.
     *
     * @return \LINE\LINEBot
     */
    public function base()
    {
        return $this->bot;
    }
}
