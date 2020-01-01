<?php

namespace Ycs77\LaravelLineBot\Test\Event;

use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Mockery as m;
use Ycs77\LaravelLineBot\Event\AudioEvent;
use Ycs77\LaravelLineBot\Event\ImageEvent;
use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Event\Transformer;
use Ycs77\LaravelLineBot\Test\TestCase;

class TransformerTest extends TestCase
{
    public function testHandleEvents()
    {
        $events = [
            m::mock(TextMessage::class),
            m::mock(ImageMessage::class),
            m::mock(AudioEvent::class),
        ];

        $transformer = new Transformer();

        $transformEvents = $transformer->handle($events);

        $this->assertCount(3, $transformEvents);
        $this->assertInstanceOf(TextEvent::class, $transformEvents[0]);
        $this->assertInstanceOf(ImageEvent::class, $transformEvents[1]);
        $this->assertInstanceOf(AudioEvent::class, $transformEvents[2]);
    }

    public function testGetNewEventClassName()
    {
        $baseEvent = m::mock(TextMessage::class);
        $transformer = new Transformer();

        $this->assertSame(TextEvent::class, $transformer->getNewEventClassName($baseEvent));
    }
}
