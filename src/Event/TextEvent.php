<?php

namespace Ycs77\LaravelLineBot\Event;

use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Event\Concerns\Eventable;
use Ycs77\LaravelLineBot\Matching\MatchedMessage;
use Ycs77\LaravelLineBot\Matching\TextMessageParameters;

class TextEvent implements Event
{
    use Eventable;

    /**
     * Get the event reply callback parameters.
     *
     * @param  \Ycs77\LaravelLineBot\Matching\MatchedMessage  $matchedMessage
     * @return array
     */
    public function getParameters(MatchedMessage $matchedMessage)
    {
        $names = TextMessageParameters::compileNames(
            $matchedMessage->getMessage()->getPattern()
        );

        return TextMessageParameters::get(
            $matchedMessage->getMatches() ?? [],
            $names
        );
    }
}
