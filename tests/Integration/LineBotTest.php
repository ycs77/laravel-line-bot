<?php

namespace Ycs77\LaravelLineBot\Test\Integration;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Response as LineResponse;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Test\TestCase;

class LineBotTest extends TestCase
{
    /** @return \Ycs77\LaravelLineBot\LineBot */
    public function bot()
    {
        return $this->app['linebot'];
    }

    public function testMatchingMessages()
    {
        $event = [
            'replyToken' => '0f3779fba3b349968c5d07db31eab56f',
            'type' => 'message',
            'mode' => 'active',
            'timestamp' => 1462629479859,
            'source' => [
                'type' => 'user',
                'userId' => 'U4af4980629...',
            ],
            'message' => [
                'id' => '325708',
                'type' => 'text',
                'text' => '哈囉',
            ],
        ];

        $events = [
            new TextMessage($event),
        ];

        $this->httpMock(function (MockInterface $mock) {
            // Post reply message
            $arguments = [
                'replyToken' => '0f3779fba3b349968c5d07db31eab56f',
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => '你好',
                    ],
                ],
            ];

            $response = new LineResponse(200, '{}');

            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/message/reply', $arguments)
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->bot()->routes($events, function () {
            $this->bot()->on()->text('哈囉', function () {
                $this->bot()->text('你好')->reply();
            });
        });
    }
}
