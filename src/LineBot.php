<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Cache\Repository as Cache;
use Illuminate\Support\Arr;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\Event\BaseEvent as LineBotEvent;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\MessageBuilder;

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
