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
     * The Line Bot talk collection instance.
     *
     * @var \Ycs77\LaravelLineBot\TalkCollection
     */
    protected $talks;

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
     * @param  \Ycs77\LaravelLineBot\TalkCollection  $talks
     * @param  \Ycs77\LaravelLineBot\Response  $response
     * @param  array  $config
     * @return void
     */
    public function __construct(BaseLINEBot $bot, Cache $cache, TalkCollection $talks, array $config = [])
    {
        $this->bot = $bot;
        $this->cache = $cache;
        $this->talks = $talks;
        $this->config = $config;
    }

    /**
     * Parse the request messages.
     *
     * @param  string  $body
     * @param  string  $signature
     * @return array
     */
    public function parseRequest(string $body, string $signature)
    {
        return $this->bot->parseEventRequest($body, $signature);
    }

    /**
     * Send the reply messages.
     *
     * @param  array  $events
     * @return void
     */
    public function reply(array $events)
    {
        foreach ($events as $event) {
            $matchedMessages = $this->talks->matchMessages($event);

            foreach ($matchedMessages as $messageBuilder) {
                $this->replyMessage($event, $messageBuilder);
            }
        }
    }

    /**
     * Create a new talk instance.
     *
     * @param  string  $type
     * @param  callable  $reply
     * @param  string  $pattern
     * @return \Ycs77\LaravelLineBot\Talk
     */
    public function talk(string $type, $reply, string $pattern = '')
    {
        $reply = Support\Closure::fromCallable($reply);

        $talk = new Talk($type, $pattern, $reply);

        $this->talks->add($talk);

        return $talk;
    }

    /**
     * Add a text message talk.
     *
     * @param  string  $pattern
     * @param  callable  $reply
     * @return \Ycs77\LaravelLineBot\Talk
     */
    public function text(string $pattern, $reply)
    {
        $this->talk(TextMessage::class, $reply, $pattern);
    }

    /**
     * Add a image message talk.
     *
     * @param  callable  $reply
     * @return \Ycs77\LaravelLineBot\Talk
     */
    public function image($reply)
    {
        $this->talk(ImageMessage::class, $reply);
    }

    /**
     * Send reply message.
     *
     * @param  \LINE\LINEBot\Event\BaseEvent  $event
     * @param  \LINE\LINEBot\MessageBuilder  $messageBuilder
     * @return \LINE\LINEBot\Response
     */
    public function replyMessage(LineBotEvent $event, MessageBuilder $messageBuilder)
    {
        return $this->bot->replyMessage($event->getReplyToken(), $messageBuilder);
    }

    /**
     * Get the User Model from Line user id.
     *
     * @param  string  $lineUserId
     * @return mixed|null
     */
    public function getUser(string $lineUserId)
    {
        if (!Arr::get($this->config, 'user.enabled')) {
            return;
        }

        $class = Arr::get($this->config, 'user.model');
        $user = (new $class)
            ->query()
            ->where(Arr::get($this->config, 'user.field'), $lineUserId)
            ->first();

        return $user ?? null;
    }

    /**
     * Create a User Model from Line user id.
     *
     * @param  string  $replyToken
     * @param  mixed  $event
     * @return mixed|null
     */
    public function createUser(string $userId, string $name)
    {
        if (!$this->config['user']['enabled']) {
            return;
        }

        if ($user = $this->getUser($userId)) {
            return $user;
        }

        // Creating...
    }

    /**
     * Get the Line user data.
     *
     * @param  string  $replyToken
     * @param  mixed  $event
     * @return array|null
     */
    public function getUserProfile(string $userId)
    {
        $response = $this->bot->getProfile($userId);

        if ($response->isSucceeded()) {
            return $response->getJSONDecodedBody();
        }
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
