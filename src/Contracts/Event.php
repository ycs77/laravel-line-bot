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
}
