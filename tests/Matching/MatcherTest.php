<?php

namespace Ycs77\LaravelLineBot\Test\Matching;

use ArrayIterator;
use Mockery as m;
use Ycs77\LaravelLineBot\Event\AudioEvent;
use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Incoming\Collection as IncomingCollection;
use Ycs77\LaravelLineBot\Incoming\IncomingMessage;
use Ycs77\LaravelLineBot\Matching\MatchedMessage;
use Ycs77\LaravelLineBot\Matching\Matcher;
use Ycs77\LaravelLineBot\Test\TestCase;

class MatcherTest extends TestCase
{
    /** @return \Ycs77\LaravelLineBot\Event\TextEvent */
    public function createMockEvent(string $text, int $times = 1)
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $baseEvent */
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('getText')
            ->times($times)
            ->andReturn($text);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Event\TextEvent $event */
        $event = m::mock(TextEvent::class);
        $event->shouldReceive('base')
            ->times($times)
            ->andReturn($baseEvent);

        return $event;
    }

    public function testMatchMessageIsMatchedMessage()
    {
        $matches = [
            0 => '我的名字是小明',
            'name' => '小明',
            1 => '小明',
        ];

        $event = $this->createMockEvent('我的名字是小明', 2);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage1 */
        $incomingMessage1 = m::mock(IncomingMessage::class);
        $incomingMessage1->shouldReceive('getEvent')
            ->once()
            ->andReturn($event);
        $incomingMessage1->shouldReceive('getPattern')
            ->once()
            ->andReturn('哈囉');

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage2 */
        $incomingMessage2 = m::mock(IncomingMessage::class);
        $incomingMessage2->shouldReceive('getEvent')
            ->twice()
            ->andReturn($event);
        $incomingMessage2->shouldReceive('getPattern')
            ->once()
            ->andReturn('我的名字是{name}');
        $incomingMessage2->shouldReceive('getExpectEventClass')
            ->once()
            ->andReturn(TextEvent::class);

        $messages = [
            $incomingMessage1,
            $incomingMessage2,
        ];

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\Collection $incomingCollection */
        $incomingCollection = m::mock(IncomingCollection::class);
        $incomingCollection->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator($messages));

        $matcher = new Matcher();
        $matchedMessage = $matcher->match($incomingCollection);

        $this->assertInstanceOf(MatchedMessage::class, $matchedMessage);
        $this->assertSame($incomingMessage2, $matchedMessage->getMessage());
        $this->assertSame($matches, $matchedMessage->getMatches());
    }

    public function testCheckMessageIsMatched()
    {
        $event = $this->createMockEvent('我的名字是小明');

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = m::mock(IncomingMessage::class);
        $incomingMessage->shouldReceive('getEvent')
            ->twice()
            ->andReturn($event);
        $incomingMessage->shouldReceive('getPattern')
            ->once()
            ->andReturn('我的名字是{name}');
        $incomingMessage->shouldReceive('getExpectEventClass')
            ->once()
            ->andReturn(TextEvent::class);

        $matcher = new Matcher();

        $this->assertTrue($matcher->isMatched($incomingMessage));
    }

    public function testCheckMessageIsMatchedAndNotTextEvent()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Event\AudioEvent $event */
        $event = m::mock(AudioEvent::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = m::mock(IncomingMessage::class);
        $incomingMessage->shouldReceive('getEvent')
            ->twice()
            ->andReturn($event);
        $incomingMessage->shouldReceive('getExpectEventClass')
            ->once()
            ->andReturn(AudioEvent::class);

        $matcher = new Matcher();

        $this->assertTrue($matcher->isMatched($incomingMessage));
    }

    public function testCheckMessageIsNotMatched()
    {
        $event = $this->createMockEvent('你的名字是小明');

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = m::mock(IncomingMessage::class);
        $incomingMessage->shouldReceive('getEvent')
            ->once()
            ->andReturn($event);
        $incomingMessage->shouldReceive('getPattern')
            ->once()
            ->andReturn('我的名字是{name}');

        $matcher = new Matcher();

        $this->assertFalse($matcher->isMatched($incomingMessage));
    }
}
