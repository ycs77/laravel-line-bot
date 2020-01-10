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

    public function testBaseEventMethods()
    {
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('getType')
            ->once()
            ->andReturn('message');
        $baseEvent->shouldReceive('getTimestamp')
            ->once()
            ->andReturn('1462629479859');
        $baseEvent->shouldReceive('getReplyToken')
            ->once()
            ->andReturn('0f3779fba3b349968c5d07db31eab56f');

        $event = m::mock(Eventable::class, [$baseEvent]);

        $this->assertSame('message', $event->getType());
        $this->assertSame('1462629479859', $event->getTimestamp());
        $this->assertSame('0f3779fba3b349968c5d07db31eab56f', $event->getReplyToken());
    }

    public function testEventSourceIsUser()
    {
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('isUserEvent')
            ->once()
            ->andReturn(true);
        $baseEvent->shouldReceive('isGroupEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isRoomEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('getUserId')
            ->once()
            ->andReturn('UID12345678');
        $baseEvent->shouldReceive('getEventSourceId')
            ->once()
            ->andReturn('UID12345678');

        $event = m::mock(Eventable::class, [$baseEvent]);

        $this->assertTrue($event->isUserEvent());
        $this->assertFalse($event->isGroupEvent());
        $this->assertFalse($event->isRoomEvent());
        $this->assertSame('UID12345678', $event->getUserId());
        $this->assertSame('UID12345678', $event->getSourceId());
    }

    public function testEventSourceIsGroup()
    {
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('isUserEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isGroupEvent')
            ->once()
            ->andReturn(true);
        $baseEvent->shouldReceive('isRoomEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('getUserId')
            ->once()
            ->andReturn('UID12345678');
        $baseEvent->shouldReceive('getGroupId')
            ->once()
            ->andReturn('GID12345678');
        $baseEvent->shouldReceive('getEventSourceId')
            ->once()
            ->andReturn('GID12345678');

        $event = m::mock(Eventable::class, [$baseEvent]);

        $this->assertFalse($event->isUserEvent());
        $this->assertTrue($event->isGroupEvent());
        $this->assertFalse($event->isRoomEvent());
        $this->assertSame('UID12345678', $event->getUserId());
        $this->assertSame('GID12345678', $event->getGroupId());
        $this->assertSame('GID12345678', $event->getSourceId());
    }

    public function testEventSourceIsRoom()
    {
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('isUserEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isGroupEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isRoomEvent')
            ->once()
            ->andReturn(true);
        $baseEvent->shouldReceive('getUserId')
            ->once()
            ->andReturn('UID12345678');
        $baseEvent->shouldReceive('getRoomId')
            ->once()
            ->andReturn('RID12345678');
        $baseEvent->shouldReceive('getEventSourceId')
            ->once()
            ->andReturn('RID12345678');

        $event = m::mock(Eventable::class, [$baseEvent]);

        $this->assertFalse($event->isUserEvent());
        $this->assertFalse($event->isGroupEvent());
        $this->assertTrue($event->isRoomEvent());
        $this->assertSame('UID12345678', $event->getUserId());
        $this->assertSame('RID12345678', $event->getRoomId());
        $this->assertSame('RID12345678', $event->getSourceId());
    }

    public function testEventSourceIsUnknown()
    {
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('isUserEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isGroupEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isRoomEvent')
            ->once()
            ->andReturn(false);
        $baseEvent->shouldReceive('isUnknownEvent')
            ->once()
            ->andReturn(true);
        $baseEvent->shouldReceive('getEventSourceId')
            ->once()
            ->andReturn(null);

        $event = m::mock(Eventable::class, [$baseEvent]);

        $this->assertFalse($event->isUserEvent());
        $this->assertFalse($event->isGroupEvent());
        $this->assertFalse($event->isRoomEvent());
        $this->assertTrue($event->isUnknownEvent());
        $this->assertNull($event->getSourceId());
    }
}
