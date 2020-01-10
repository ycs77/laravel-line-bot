<?php

namespace Ycs77\LaravelLineBot;

use Closure;
use Ycs77\LaravelLineBot\Event\AccountLinkEvent;
use Ycs77\LaravelLineBot\Event\AudioEvent;
use Ycs77\LaravelLineBot\Event\FallbackEvent;
use Ycs77\LaravelLineBot\Event\FileEvent;
use Ycs77\LaravelLineBot\Event\FollowEvent;
use Ycs77\LaravelLineBot\Event\ImageEvent;
use Ycs77\LaravelLineBot\Event\JoinEvent;
use Ycs77\LaravelLineBot\Event\LeaveEvent;
use Ycs77\LaravelLineBot\Event\LocationEvent;
use Ycs77\LaravelLineBot\Event\MemberJoinEvent;
use Ycs77\LaravelLineBot\Event\MemberLeaveEvent;
use Ycs77\LaravelLineBot\Event\PostbackEvent;
use Ycs77\LaravelLineBot\Event\StickerEvent;
use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Event\UnfollowEvent;
use Ycs77\LaravelLineBot\Event\VideoEvent;
use Ycs77\LaravelLineBot\Incoming\Collection;
use Ycs77\LaravelLineBot\Incoming\IncomingMessage;

class MessageRouter
{
    /**
     * The Line Bot instance.
     *
     * @var \Ycs77\LaravelLineBot\LineBot
     */
    protected $bot;

    /**
     * The message collection instance.
     *
     * @var \Ycs77\LaravelLineBot\Incoming\Collection
     */
    protected $messages;

    /**
     * Create a new Line Bot incoming message manager instance.
     *
     * @param  \Ycs77\LaravelLineBot\LineBot  $bot
     * @return void
     */
    public function __construct(LineBot $bot)
    {
        $this->bot = $bot;
        $this->messages = $this->newCollection();
    }

    /**
     * Add incoming message.
     *
     * @param  string  $eventClass
     * @param  \Closure  $callback
     * @return \Ycs77\LaravelLineBot\Incoming\IncomingMessage
     */
    public function add(string $eventClass, Closure $callback)
    {
        $incomingMessage = $this->newMessage($eventClass, $callback);

        $this->messages->add($incomingMessage);

        return $incomingMessage;
    }

    /**
     * Make a new incoming message instance.
     *
     * @param  string  $eventClass
     * @param  \Closure  $callback
     * @return \Ycs77\LaravelLineBot\Incoming\IncomingMessage
     */
    public function newMessage(string $eventClass, Closure $callback)
    {
        return new IncomingMessage($this->bot->getEvent(), $eventClass, $callback);
    }

    /**
     * Listen the text message event.
     *
     * @param  string  $pattern
     * @param  \Closure  $callback
     * @return self
     */
    public function text(string $pattern, Closure $callback)
    {
        $message = $this->add(TextEvent::class, $callback);
        $message->setPattern($pattern);

        return $this;
    }

    /**
     * Listen the image message event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function image(Closure $callback)
    {
        $this->add(ImageEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the video message event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function video(Closure $callback)
    {
        $this->add(VideoEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the audio message event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function audio(Closure $callback)
    {
        $this->add(AudioEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the file message event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function file(Closure $callback)
    {
        $this->add(FileEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the location message event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function location(Closure $callback)
    {
        $this->add(LocationEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the sticker message event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function sticker(Closure $callback)
    {
        $this->add(StickerEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the follow event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function follow(Closure $callback)
    {
        $this->add(FollowEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the unfollow event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function unfollow(Closure $callback)
    {
        $this->add(UnfollowEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the joinr event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function join(Closure $callback)
    {
        $this->add(JoinEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the leave event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function leave(Closure $callback)
    {
        $this->add(LeaveEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the member join event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function memberJoin(Closure $callback)
    {
        $this->add(MemberJoinEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the member leave event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function memberLeave(Closure $callback)
    {
        $this->add(MemberLeaveEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the postback event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function postback(Closure $callback)
    {
        $this->add(PostbackEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the account link event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function accountLink(Closure $callback)
    {
        $this->add(AccountLinkEvent::class, $callback);

        return $this;
    }

    /**
     * Listen the fallback event.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function fallback(Closure $callback)
    {
        $this->add(FallbackEvent::class, $callback);

        return $this;
    }

    /**
     * Create a new message collection instance.
     *
     * @return \Ycs77\LaravelLineBot\Incoming\Collection
     */
    public function newCollection()
    {
        return new Collection();
    }

    /**
     * Get the message collection instance.
     *
     * @return \Ycs77\LaravelLineBot\Incoming\Collection
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
