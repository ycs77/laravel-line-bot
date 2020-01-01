<?php

namespace Ycs77\LaravelLineBot\Test\Matching;

use Mockery as m;
use Ycs77\LaravelLineBot\Incoming\IncomingMessage;
use Ycs77\LaravelLineBot\Matching\MatchedMessage;
use Ycs77\LaravelLineBot\Test\TestCase;

class MatchedMessageTest extends TestCase
{
    public function testNewMatchedMessage()
    {
        $matches = [
            0 => '我的名字是小明',
            'name' => '小明',
            1 => '小明',
        ];

        /** @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = m::mock(IncomingMessage::class);

        $matchedMessage = new MatchedMessage($incomingMessage, $matches);

        $this->assertSame($incomingMessage, $matchedMessage->getMessage());
        $this->assertSame($matches, $matchedMessage->getMatches());
    }

    public function testNewMatchedMessageMatchIsNull()
    {
        $matches = null;

        /** @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = m::mock(IncomingMessage::class);

        $matchedMessage = new MatchedMessage($incomingMessage, $matches);

        $this->assertSame($incomingMessage, $matchedMessage->getMessage());
        $this->assertNull($matchedMessage->getMatches());
    }
}
