<?php

namespace Ycs77\LaravelLineBot\Test;

use LINE\LINEBot\Constant\MessageContentProviderType;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Ycs77\LaravelLineBot\Matcher;
use Ycs77\LaravelLineBot\Talk;

class MatcherTest extends TestCase
{
    /**
     * Dataprovider:
     *  [[$input, $pattern, $expected]].
     *
     * @return array
     */
    public function incoming_message_provider()
    {
        $messages = [
            ['foo', 'foo', true],
            ['foo ', 'foo', true],
            ['foo ', 'foo ', true],

            ['foo', '{command}', true],
            ['foo ', '{command}', true],
            ['foo ', '{command} ', true],

            ['call me foo', 'call me {name}', true],
            ['call me foo ', 'call me {name}', true],
            ['call me foo ', 'call me {name} ', true],

            ['call me foo baz', 'call me {name} {surname}', true],
            ['call me foo baz ', 'call me {name} {surname}', true],
            ['call me foo baz ', 'call me {name} {surname} ', true],

            ['/foo', '/foo', true],
            ['/foo ', '/foo', true],
            ['/foo ', '/foo ', true],

            ['/foo', '/{command}', true],
            ['/foo ', '/{command}', true],
            ['/foo ', '/{command} ', true],

            ['/call me foo', '/call me {name}', true],
            ['/call me foo ', '/call me {name}', true],
            ['/call me foo ', '/call me {name} ', true],

            ['/call me foo baz', '/call me {name} {surname}', true],
            ['/call me foo baz ', '/call me {name} {surname}', true],
            ['/call me foo baz ', '/call me {name} {surname} ', true],

            ['!foo', '!foo', true],
            ['!foo ', '!foo', true],
            ['!foo ', '!foo ', true],

            ['!foo', '!{command}', true],
            ['!foo ', '!{command}', true],
            ['!foo ', '!{command} ', true],

            ['!call me foo', '!call me {name}', true],
            ['!call me foo ', '!call me {name}', true],
            ['!call me foo ', '!call me {name} ', true],

            ['!call me foo baz', '!call me {name} {surname}', true],
            ['!call me foo baz ', '!call me {name} {surname}', true],
            ['!call me foo baz ', '!call me {name} {surname} ', true],

            ['@foo', '@foo', true],
            ['@foo ', '@foo', true],
            ['@foo ', '@foo ', true],

            ['@foo', '@{command}', true],
            ['@foo ', '@{command}', true],
            ['@foo ', '@{command} ', true],

            ['@call me foo', '@call me {name}', true],
            ['@call me foo ', '@call me {name}', true],
            ['@call me foo ', '@call me {name} ', true],

            ['@call me foo baz', '@call me {name} {surname}', true],
            ['@call me foo baz ', '@call me {name} {surname}', true],
            ['@call me foo baz ', '@call me {name} {surname} ', true],

            ['#foo', '#foo', true],
            ['#foo ', '#foo', true],
            ['#foo ', '#foo ', true],

            ['#foo', '#{command}', true],
            ['#foo ', '#{command}', true],
            ['#foo ', '#{command} ', true],

            ['#call me foo', '#call me {name}', true],
            ['#call me foo ', '#call me {name}', true],
            ['#call me foo ', '#call me {name} ', true],

            ['#call me foo baz', '#call me {name} {surname}', true],
            ['#call me foo baz ', '#call me {name} {surname}', true],
            ['#call me foo baz ', '#call me {name} {surname} ', true],

            ['!@#2f00', '!@#2f00', true],
            ['!@#2f00 ', '!@#2f00', true],
            ['!@#2f00 ', '!@#2f00 ', true],

            ['!@#2c@ll m3 f00', '!@#2c@ll m3 {nam3}', true],
            ['!@#2c@ll m3 f00 ', '!@#2c@ll m3 {nam3}', true],
            ['!@#2c@ll m3 f00 ', '!@#2c@ll m3 {nam3} ', true],

            ['!@#2c@ll m3 f00 baz', '!@#2c@ll m3 {nam3} {surnam3}', true],
            ['!@#2c@ll m3 f00 baz ', '!@#2c@ll m3 {nam3} {surnam3}', true],
            ['!@#2c@ll m3 f00 baz ', '!@#2c@ll m3 {nam3} {surnam3} ', true],

            [' foo', 'foo', false],
            [' foo', 'foo ', false],
            [' foo ', 'foo ', false],

            ['foo', 'baz', false],
            ['foo ', 'baz', false],
            ['foo ', 'baz ', false],
        ];

        return $messages;
    }

    /**
     * @dataProvider incoming_message_provider
     *
     * @param  string  $message
     * @param  string  $pattern
     * @param  bool  $expected
     * @return void
     */
    public function testIsMatchTextMessage(string $message, string $pattern, bool $expected)
    {
        $matcher = new Matcher();
        $talk = new Talk(TextMessage::class, $pattern, function () {
        });
        $event = new TextMessage([
            'message' => [
                'type' => 'text',
                'text' => $message,
            ],
        ]);

        $this->assertSame($expected, $matcher->isMatch($talk, $event),
            sprintf('Message `%s` and pattern `%s` should assert `%s`',
                $message,
                $pattern,
                $expected ? 'true' : 'false')
        );
    }

    public function testIsMatchImageMessage()
    {
        $matcher = new Matcher();
        $talk = new Talk(ImageMessage::class, '', function () {
        });
        $event = new ImageMessage([
            'message' => [
                'contentProvider' => [
                    'type' => MessageContentProviderType::EXTERNAL,
                    'originalContentUrl' => '...',
                    'previewImageUrl' => '...',
                ],
            ],
        ]);

        $this->assertTrue($matcher->isMatch($talk, $event));
    }
}
