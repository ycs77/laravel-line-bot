<?php

namespace Ycs77\LaravelLineBot\Contracts;

use Ycs77\LaravelLineBot\Matching\MatchedMessage;

interface MessageEvent extends Event
{
    /**
     * Get the event reply callback parameters.
     *
     * @param  \Ycs77\LaravelLineBot\Matching\MatchedMessage  $matchedMessage
     * @return array
     */
    public function getParameters(MatchedMessage $matchedMessage);
}
