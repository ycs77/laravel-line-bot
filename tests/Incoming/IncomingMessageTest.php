<?php

namespace Ycs77\LaravelLineBot\Test\Incoming;

use Closure;
use Mockery as m;
use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Event\FallbackEvent;
use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Incoming\IncomingMessage;
use Ycs77\LaravelLineBot\Test\TestCase;

class IncomingMessageTest extends TestCase
{
    public function createIncomingMessage(Event $event, string $expectEventClass)
    {
        return new IncomingMessage($event, $expectEventClass, function () {
            //
        });
    }

    public function testNewIncomingMessage()
    {
        /** @var \Ycs77\LaravelLineBot\Contracts\Event $event */
        $event = m::mock(Event::class);

        $incomingMessage = $this->createIncomingMessage($event, Event::class);

        $this->assertSame($event, $incomingMessage->getEvent());
        $this->assertSame(Event::class, $incomingMessage->getExpectEventClass());
        $this->assertInstanceOf(Closure::class, $incomingMessage->getReplyCallback());
        $this->assertNull($incomingMessage->getPattern());
        $this->assertFalse($incomingMessage->isFallback());
    }

    public function testGetAndSetEventFromIncomingMessage()
    {
        /** @var \Ycs77\LaravelLineBot\Contracts\Event $event */
        $event = m::mock(Event::class);

        /** @var \Ycs77\LaravelLineBot\Contracts\Event $newEvent */
        $newEvent = m::mock(Event::class);

        $incomingMessage = $this->createIncomingMessage($event, Event::class);
        $this->assertSame($event, $incomingMessage->getEvent());

        $incomingMessage->setEvent($newEvent);
        $this->assertSame($newEvent, $incomingMessage->getEvent());
    }

    public function testNewTextIncomingMessage()
    {
        /** @var \Ycs77\LaravelLineBot\Event\TextEvent $event */
        $event = m::mock(TextEvent::class);

        $incomingMessage = $this->createIncomingMessage($event, TextEvent::class);

        $incomingMessage->setPattern('我的名字是{name}，今年{age}歲');

        $this->assertSame($event, $incomingMessage->getEvent());
        $this->assertSame(TextEvent::class, $incomingMessage->getExpectEventClass());
        $this->assertInstanceOf(Closure::class, $incomingMessage->getReplyCallback());
        $this->assertSame('我的名字是{name}，今年{age}歲', $incomingMessage->getPattern());
        $this->assertFalse($incomingMessage->isFallback());
    }

    public function testNewFallbackIncomingMessage()
    {
        /** @var \Ycs77\LaravelLineBot\Event\FallbackEvent $event */
        $event = m::mock(FallbackEvent::class);

        $incomingMessage = $this->createIncomingMessage($event, FallbackEvent::class);

        $this->assertSame($event, $incomingMessage->getEvent());
        $this->assertSame(FallbackEvent::class, $incomingMessage->getExpectEventClass());
        $this->assertInstanceOf(Closure::class, $incomingMessage->getReplyCallback());
        $this->assertNull($incomingMessage->getPattern());
        $this->assertTrue($incomingMessage->isFallback());
    }
}
