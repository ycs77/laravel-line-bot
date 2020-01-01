<?php

namespace Ycs77\LaravelLineBot\Matching;

use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Incoming\Collection;
use Ycs77\LaravelLineBot\Incoming\IncomingMessage;

class Matcher
{
    /**
     * The matcher pattern text.
     *
     * @var string
     */
    const PATTERN = '/\{((?:(?!\d+,?\d+?)\w)+?)\}/';

    /**
     * The matcher matches result.
     *
     * @var array|null
     */
    protected $matches;

    /**
     * Matching message.
     *
     * @param  \Ycs77\LaravelLineBot\Incoming\Collection  $collection
     * @return \Ycs77\LaravelLineBot\Matching\MatchedMessage|null
     */
    public function match(Collection $messages)
    {
        /** @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        foreach ($messages as $incomingMessage) {
            if ($this->isMatched($incomingMessage)) {
                return new MatchedMessage($incomingMessage, $this->matches);
            }
        }
    }

    /**
     * Check the incoming message is matched.
     *
     * @param  \Ycs77\LaravelLineBot\Incoming\IncomingMessage  $incomingMessage
     * @return bool
     */
    public function isMatched(IncomingMessage $incomingMessage)
    {
        return $this->isMatchedMessage($incomingMessage)
            && $this->isMatchedEvent($incomingMessage);
    }

    /**
     * Check the message is matched.
     *
     * @param  \Ycs77\LaravelLineBot\Incoming\IncomingMessage  $incomingMessage
     * @return bool
     */
    public function isMatchedMessage(IncomingMessage $incomingMessage)
    {
        $event = $incomingMessage->getEvent();

        // If it is not text message event, don't verify that it matches.
        if (!$event instanceof TextEvent) {
            return true;
        }

        /** @var \Ycs77\LaravelLineBot\Event\TextEvent $event */
        $pattern = '/^' . preg_replace(static::PATTERN, '(?<$1>.*)', $incomingMessage->getPattern()) . ' ?$/miu';

        return (bool) preg_match($pattern, $event->base()->getText(), $this->matches);
    }

    /**
     * Check the message event is matched.
     *
     * @param  \Ycs77\LaravelLineBot\Incoming\IncomingMessage  $incomingMessage
     * @return bool
     */
    public function isMatchedEvent(IncomingMessage $incomingMessage)
    {
        $expectEventClass = $incomingMessage->getExpectEventClass();

        return $incomingMessage->getEvent() instanceof $expectEventClass;
    }
}
