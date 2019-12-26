<?php

namespace Ycs77\LaravelLineBot\Test\Message;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Ycs77\LaravelLineBot\Message\Text;
use Ycs77\LaravelLineBot\Test\TestCase;

class TextTest extends TestCase
{
    public function testGetMessageBuilder()
    {
        $message = new Text('message');

        $this->assertInstanceOf(TextMessageBuilder::class, $message->getMessageBuilder());
    }
}
