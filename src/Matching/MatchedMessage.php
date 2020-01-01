<?php

namespace Ycs77\LaravelLineBot\Matching;

use Ycs77\LaravelLineBot\Incoming\IncomingMessage;

class MatchedMessage
{
    /**
     * The incoming message instance.
     *
     * @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage
     */
    protected $incomingMessage;

    /**
     * The message matches array.
     *
     * @var array|null
     */
    protected $matches;

    /**
     * Create a new matched message instance.
     *
     * @param  \Ycs77\LaravelLineBot\Incoming\IncomingMessage  $incomingMessage
     * @param  array|null  $matches
     * @return void
     */
    public function __construct(IncomingMessage $incomingMessage, array $matches = null)
    {
        $this->incomingMessage = $incomingMessage;
        $this->matches = $matches;
    }

    /**
     * Get the incoming message instance.
     *
     * @return \Ycs77\LaravelLineBot\Incoming\IncomingMessage
     */
    public function getMessage()
    {
        return $this->incomingMessage;
    }

    /**
     * Get the message matches array.
     *
     * @return array|null
     */
    public function getMatches()
    {
        return $this->matches;
    }
}
