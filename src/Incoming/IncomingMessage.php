<?php

namespace Ycs77\LaravelLineBot\Incoming;

use Closure;
use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Event\FallbackEvent;

class IncomingMessage
{
    /**
     * The catched event instance.
     *
     * @var \Ycs77\LaravelLineBot\Contracts\Event
     */
    protected $event;

    /**
     * The incoming message expect event class name.
     *
     * @var string
     */
    protected $expectEventClass;

    /**
     * The incoming message reply callback function.
     *
     * @var \Closure
     */
    protected $replyCallback;

    /**
     * The incoming message match pattern for text message.
     *
     * @var string|null
     */
    protected $pattern;

    /**
     * Create a new incoming message instance.
     *
     * @param  \Ycs77\LaravelLineBot\Contracts\Event  $event
     * @param  string  $expectEventClass
     * @param  \Closure  $replyCallback
     * @return void
     */
    public function __construct(Event $event, string $expectEventClass, Closure $replyCallback)
    {
        $this->event = $event;
        $this->expectEventClass = $expectEventClass;
        $this->replyCallback = $replyCallback;
    }

    /**
     * Get the incoming message expected event class name.
     *
     * @return \Ycs77\LaravelLineBot\Contracts\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Get the incoming message expected event class name.
     *
     * @return string
     */
    public function getExpectEventClass()
    {
        return $this->expectEventClass;
    }

    /**
     * Get the incoming message reply callback function.
     *
     * @return \Closure
     */
    public function getReplyCallback()
    {
        return $this->replyCallback;
    }

    /**
     * Get the incoming message match pattern for text message.
     *
     * @return string|null
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Set the incoming message match pattern for text message.
     *
     * @param  string
     * @return self
     */
    public function setPattern(string $pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Check the incoming message is fallback.
     *
     * @return bool
     */
    public function isFallback()
    {
        return $this->expectEventClass === FallbackEvent::class;
    }
}
