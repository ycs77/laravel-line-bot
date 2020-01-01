<?php

namespace Ycs77\LaravelLineBot\Contracts;

interface Event
{
    /**
     * Get the base event instance.
     *
     * @return \LINE\LINEBot\Event\BaseEvent
     */
    public function base();
}
