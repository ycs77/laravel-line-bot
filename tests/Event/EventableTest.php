<?php

namespace Ycs77\LaravelLineBot\Test\Event;

use LINE\LINEBot\Event\BaseEvent;
use Mockery as m;
use Ycs77\LaravelLineBot\Event\Concerns\Eventable;
use Ycs77\LaravelLineBot\Matching\MatchedMessage;
use Ycs77\LaravelLineBot\Test\TestCase;

class EventableTest extends TestCase
{
    public function testGetBaseEvent()
    {
        $baseEvent = m::mock(BaseEvent::class);
        $event = m::mock(Eventable::class, [$baseEvent]);

        $this->assertSame($baseEvent, $event->base());
    }

    public function testGetParameters()
    {
        $event = m::mock(Eventable::class);

        /** @var \Ycs77\LaravelLineBot\Matching\MatchedMessage $matchedMessage */
        $matchedMessage = m::mock(MatchedMessage::class);

        $this->assertSame([], $event->getParameters($matchedMessage));
    }
}
