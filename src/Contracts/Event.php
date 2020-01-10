<?php

namespace Ycs77\LaravelLineBot\Contracts;

use Ycs77\LaravelLineBot\Matching\MatchedMessage;

interface Event
{
    /**
     * Get the base event instance.
     *
     * @return \LINE\LINEBot\Event\BaseEvent
     */
    public function base();

    /**
     * Get the event reply callback parameters.
     *
     * @param  \Ycs77\LaravelLineBot\Matching\MatchedMessage  $matchedMessage
     * @return array
     */
    public function getParameters(MatchedMessage $matchedMessage);

    /**
     * Get the event type.
     *
     * @return string
     */
    public function getType();

    /**
     * Get the event timestamp.
     *
     * @return int
     */
    public function getTimestamp();

    /**
     * Get the message reply token.
     *
     * @return string|null
     */
    public function getReplyToken();

    /**
     * Check the event source type is user.
     *
     * @return bool
     */
    public function isUserEvent();

    /**
     * Check the event source type is group.
     *
     * @return bool
     */
    public function isGroupEvent();

    /**
     * Check the event source type is room.
     *
     * @return bool
     */
    public function isRoomEvent();

    /**
     * Check the event source type is unknown.
     *
     * @return bool
     */
    public function isUnknownEvent();

    /**
     * Get the user id.
     *
     * @return string|null
     */
    public function getUserId();

    /**
     * Get the group id.
     *
     * @return string|null
     */
    public function getGroupId();

    /**
     * Get the room id.
     *
     * @return string|null
     */
    public function getRoomId();

    /**
     * Get the source id.
     *
     * @return string
     */
    public function getSourceId();
}
