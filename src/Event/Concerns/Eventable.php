<?php

namespace Ycs77\LaravelLineBot\Event\Concerns;

use LINE\LINEBot\Event\BaseEvent;
use Ycs77\LaravelLineBot\Matching\MatchedMessage;

trait Eventable
{
    /**
     * The base event instance.
     *
     * @var \LINE\LINEBot\Event\BaseEvent
     */
    protected $baseEvent;

    /**
     * Create a new event instance.
     *
     * @param \LINE\LINEBot\Event\BaseEvent $baseEvent
     */
    public function __construct(BaseEvent $baseEvent)
    {
        $this->baseEvent = $baseEvent;
    }

    /**
     * Get the base event instance.
     *
     * @return \LINE\LINEBot\Event\BaseEvent
     */
    public function base()
    {
        return $this->baseEvent;
    }

    /**
     * Get the event reply callback parameters.
     *
     * @param  \Ycs77\LaravelLineBot\Matching\MatchedMessage  $matchedMessage
     * @return array
     */
    public function getParameters(MatchedMessage $matchedMessage)
    {
        return [];
    }
}
