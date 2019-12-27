<?php

namespace Ycs77\LaravelLineBot;

use Closure;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\MessageBuilder;
use Ycs77\LaravelLineBot\Message\Builder;

class LineBot
{
    /**
     * The base Line Bot SDK instance.
     *
     * @var \LINE\LINEBot
     */
    protected $bot;

    /**
     * The config repository interface.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The cache repository interface.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The Line Bot event instance.
     *
     * @var \LINE\LINEBot\Event\BaseEvent|null
     */
    protected $event;

    /**
     * Create a new Line Bot SDK instance.
     *
     * @param  \LINE\LINEBot  $bot
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return void
     */
    public function __construct(BaseLINEBot $bot, Config $config, Cache $cache)
    {
        $this->bot = $bot;
        $this->config = $config;
        $this->cache = $cache;
    }

    /**
     * Reply message.
     *
     * @param  \LINE\LINEBot\MessageBuilder  $messageBuilder
     * @return \LINE\LINEBot\Response|null
     */
    public function reply(MessageBuilder $messageBuilder)
    {
        if (!$this->event) {
            return;
        }

        return $this->bot->replyMessage(
            $this->event->getReplyToken(),
            $messageBuilder
        );
    }

    /**
     * Begin querying the Line Bot message.
     *
     * @return \Ycs77\LaravelLineBot\Message\Builder
     */
    public function query()
    {
        return new Builder($this);
    }

    /**
     * Get the Line Bot action.
     *
     * @return \Ycs77\LaravelLineBot\Action
     */
    public function action()
    {
        return new Action();
    }

    /**
     * Registration matches message routing.
     *
     * @param  array  $events
     * @param  \Closure  $callback
     * @return void
     */
    public function routes(array $events, Closure $callback)
    {
        array_map(function ($event) use ($callback) {
            $this->setEvent($event);

            call_user_func($callback, $event);
        }, $events);
    }

    /**
     * Get the config repository instance.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \Illuminate\Contracts\Config\Repository|mixed
     */
    public function getConfig($key = null, $default = null)
    {
        if (!is_null($key)) {
            return $this->config->get($key, $default);
        }

        return $this->config;
    }

    /**
     * Set the Line Bot event instance.
     *
     * @param  \LINE\LINEBot\Event\BaseEvent  $event
     * @return self
     */
    public function setEvent(BaseEvent $event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get the Line Bot event instance.
     *
     * @return \LINE\LINEBot\Event\BaseEvent|null
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Handle dynamic method calls into the Line Bot.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->query()->$method(...$parameters);
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
