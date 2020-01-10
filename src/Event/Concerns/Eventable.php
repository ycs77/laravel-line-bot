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
     * Get the event type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->baseEvent->getType();
    }

    /**
     * Get the event timestamp.
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->baseEvent->getTimestamp();
    }

    /**
     * Get the message reply token.
     *
     * @return string|null
     */
    public function getReplyToken()
    {
        return $this->baseEvent->getReplyToken();
    }

    /**
     * Check the event source type is user.
     *
     * @return bool
     */
    public function isUserEvent()
    {
        return $this->baseEvent->isUserEvent();
    }

    /**
     * Check the event source type is group.
     *
     * @return bool
     */
    public function isGroupEvent()
    {
        return $this->baseEvent->isGroupEvent();
    }

    /**
     * Check the event source type is room.
     *
     * @return bool
     */
    public function isRoomEvent()
    {
        return $this->baseEvent->isRoomEvent();
    }

    /**
     * Check the event source type is unknown.
     *
     * @return bool
     */
    public function isUnknownEvent()
    {
        return $this->baseEvent->isUnknownEvent();
    }

    /**
     * Get the user id.
     *
     * @return string|null
     */
    public function getUserId()
    {
        return $this->baseEvent->getUserId();
    }

    /**
     * Get the group id.
     *
     * @return string|null
     */
    public function getGroupId()
    {
        return $this->baseEvent->getGroupId();
    }

    /**
     * Get the room id.
     *
     * @return string|null
     */
    public function getRoomId()
    {
        return $this->baseEvent->getRoomId();
    }

    /**
     * Get the source id.
     *
     * @return string
     */
    public function getSourceId()
    {
        return $this->baseEvent->getEventSourceId();
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
