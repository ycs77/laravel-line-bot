<?php

namespace Ycs77\LaravelLineBot\Contracts;

interface Message
{
    /**
     * Get the message builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function getMessageBuilder();
}
