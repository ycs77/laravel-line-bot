<?php

namespace Ycs77\LaravelLineBot\Test\Integration;

use LINE\LINEBot\Response as LineResponse;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Response;
use Ycs77\LaravelLineBot\Test\Stubs\Controllers\BaseConversationController;
use Ycs77\LaravelLineBot\Test\TestCase;

class LineBotConversationTest extends TestCase
{
    public function testBaseConversation()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Post reply message
            $response = new LineResponse(200, '{}');

            $arguments = [
                'replyToken' => 'tokenxxxxxxxxx01',
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => '請問你的名字是?',
                    ],
                ],
            ];
            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/message/reply', $arguments)
                ->once()
                ->andReturn($response);

            $arguments = [
                'replyToken' => 'tokenxxxxxxxxx02',
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => '請問你的年齡是?',
                    ],
                ],
            ];
            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/message/reply', $arguments)
                ->once()
                ->andReturn($response);

            $arguments = [
                'replyToken' => 'tokenxxxxxxxxx03',
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => '你好Lucas，今年20歲',
                    ],
                ],
            ];
            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/message/reply', $arguments)
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $expected = [
            'state' => true,
        ];

        $response = $this->app->make(Response::class);

        $controller = new BaseConversationController();

        $request = $this->createRequest(
            'DyOJ3l7ISbmXb7z6rZMvZMwm105fN0k5fw8vLi50T8c=',
            '{"destination":"xxxxxxxxxx","events":[{"replyToken":"tokenxxxxxxxxx01","type":"message","timestamp":1462629479859,"source":{"type":"user","userId":"UID12345678"},"message":{"id":"325708","type":"text","text":"問答開始"}}]}'
        );
        $actual = $controller->webhook($request, $response);
        $this->assertSame($expected, $actual->getData(true));

        $request = $this->createRequest(
            'DyOJ3l7ISbmXb7z6rZMvZMwm105fN0k5fw8vLi50T8c=',
            '{"destination":"xxxxxxxxxx","events":[{"replyToken":"tokenxxxxxxxxx02","type":"message","timestamp":1462629479859,"source":{"type":"user","userId":"UID12345678"},"message":{"id":"325708","type":"text","text":"Lucas"}}]}'
        );
        $actual = $controller->webhook($request, $response);
        $this->assertSame($expected, $actual->getData(true));

        $request = $this->createRequest(
            'DyOJ3l7ISbmXb7z6rZMvZMwm105fN0k5fw8vLi50T8c=',
            '{"destination":"xxxxxxxxxx","events":[{"replyToken":"tokenxxxxxxxxx03","type":"message","timestamp":1462629479859,"source":{"type":"user","userId":"UID12345678"},"message":{"id":"325708","type":"text","text":"20"}}]}'
        );
        $actual = $controller->webhook($request, $response);
        $this->assertSame($expected, $actual->getData(true));
    }
}
