<?php

namespace Ycs77\LaravelLineBot;

use Closure;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\MessageBuilder;
use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Event\Transformer as EventTransformer;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;
use Ycs77\LaravelLineBot\File\Factory as FileFactory;
use Ycs77\LaravelLineBot\Matching\Matcher;
use Ycs77\LaravelLineBot\Message\Builder;

/**
 * @method static \Ycs77\LaravelLineBot\Message\Builder text(string $message)
 * @method static \Ycs77\LaravelLineBot\Message\Builder template(string|\LINE\LINEBot\MessageBuilder\TemplateMessageBuilder $altText, \Closure $callback)
 * @method static \Ycs77\LaravelLineBot\Message\Builder quickReply(\Ycs77\LaravelLineBot\QuickReply|\Closure $value)
 *
 * @see \Ycs77\LaravelLineBot\Message\Builder
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
     * The config repository instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The cache repository instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The Line Bot event instance.
     *
     * @var \Ycs77\LaravelLineBot\Contracts\Event|null
     */
    protected $event;

    /**
     * The Line Bot message router instance.
     *
     * @var \Ycs77\LaravelLineBot\MessageRouter
     */
    protected $router;

    /**
     * The Line Bot message matcher instance.
     *
     * @var \Ycs77\LaravelLineBot\Matching\Matcher
     */
    protected $matcher;

    /**
     * The file factory instance.
     *
     * @var \Ycs77\LaravelLineBot\File\Factory
     */
    protected $file;

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
        $this->router = new MessageRouter($this);
        $this->matcher = new Matcher();
        $this->file = new FileFactory();
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
            $this->event->base()->getReplyToken(),
            $messageBuilder
        );
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
        $events = $this->eventTransform($events);

        /** @param \Ycs77\LaravelLineBot\Contracts\Event $event */
        array_walk($events, function ($event) use ($callback) {
            $this->setEvent($event);

            // Register incoming messages.
            call_user_func($callback, $event);

            $messages = $this->router->getMessages();

            // If has matched message, call the message reply callback.
            if ($matchedMessage = $this->matcher->match($messages)) {
                call_user_func_array(
                    $matchedMessage->getMessage()->getReplyCallback(),
                    $event->getParameters($matchedMessage)
                );
            } else {
                // Call the fallback reply callback.
                if ($fallbackMessage = $messages->getFallback()) {
                    call_user_func($fallbackMessage->getReplyCallback());
                }
            }
        });
    }

    /**
     * Transform the events to new event instance.
     *
     * @param  array  $events
     * @return array
     */
    public function eventTransform(array $events)
    {
        return EventTransformer::handle($events);
    }

    /**
     * Get file from Line base bot.
     *
     * @param  string  $messageId
     * @param  string  $filePath
     * @return \Illuminate\Http\File
     */
    public function file(string $messageId, string $filePath = 'linebot')
    {
        $response = $this->bot->getMessageContent($messageId);

        if (!$response->isSucceeded()) {
            throw new LineRequestErrorException('Error with getting LineBot message content');
        }

        $content = $response->getRawBody();

        return $this->file->create($content, $filePath);
    }

    /**
     * Begin querying the Line Bot message.
     *
     * @return \Ycs77\LaravelLineBot\Message\Builder
     */
    public function say()
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
     * Get the Line Bot message router instance.
     *
     * @return \Ycs77\LaravelLineBot\MessageRouter
     */
    public function on()
    {
        return $this->getRouter();
    }

    /**
     * Get the Line Bot message router instance.
     *
     * @return \Ycs77\LaravelLineBot\MessageRouter
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set the Line Bot message router instance.
     *
     * @param  \Ycs77\LaravelLineBot\MessageRouter|\Closure  $router
     * @return self
     */
    public function setRouter($router)
    {
        $this->router = $router instanceof Closure
            ? call_user_func($router, $this)
            : $router;

        return $this;
    }

    /**
     * Get the Line Bot message matcher instance.
     *
     * @return \Ycs77\LaravelLineBot\Matching\Matcher
     */
    public function getMatcher()
    {
        return $this->matcher;
    }

    /**
     * Set the Line Bot message matcher instance.
     *
     * @param  \Ycs77\LaravelLineBot\Matching\Matcher  $matcher
     * @return self
     */
    public function setMatcher(Matcher $matcher)
    {
        $this->matcher = $matcher;

        return $this;
    }

    /**
     * Get the file factory instance.
     *
     * @return \Ycs77\LaravelLineBot\File\Factory
     */
    public function getFileFactory()
    {
        return $this->file;
    }

    /**
     * Set the file factory instance.
     *
     * @param  \Ycs77\LaravelLineBot\File\Factory  $file
     * @return self
     */
    public function setFileFactory(FileFactory $file)
    {
        $this->file = $file;

        return $this;
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
     * Get the Line Bot event instance.
     *
     * @return \Ycs77\LaravelLineBot\Contracts\Event|null
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set the Line Bot event instance.
     *
     * @param  \Ycs77\LaravelLineBot\Contracts\Event  $event
     * @return self
     */
    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
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

    /**
     * Handle dynamic method calls into the Line Bot.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->say()->$method(...$parameters);
    }
}
