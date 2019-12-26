<?php

namespace Ycs77\LaravelLineBot\Test\Integration;

use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Response as LineResponse;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Response;
use Ycs77\LaravelLineBot\Test\Stubs\StubController;
use Ycs77\LaravelLineBot\Test\TestCase;

class LineBotControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('app.debug', true);
        $this->app['config']->set('linebot', [
            'channel_access_token' => 'token_123456',
            'channel_secret' => 'secret_123456',
        ]);
    }

    public function createRequest(string $signature, string $content)
    {
        $request = new Request([], [], [], [], [], [], $content);
        $request->headers->set(HTTPHeader::LINE_SIGNATURE, $signature);

        return $request;
    }

    public function testReplyMessage()
    {
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

        $expected = [
            'state' => true,
        ];

        $signature = 'gsrBt6eFskfPn1MyDf8WsRjSArBECpAbW1Z9AbsBY5s=';
        $content = '{"destination":"xxxxxxxxxx","events":[{"replyToken":"0f3779fba3b349968c5d07db31eab56f","type":"message","timestamp":1462629479859,"source":{"type":"user","userId":"UID12345678"},"message":{"id":"325708","type":"text","text":"嗨"}}]}';
        $request = $this->createRequest($signature, $content);

        $response = $this->app->make(Response::class);

        $controller = new StubController();

        $actual = $controller->webhook($request, $response);

        $this->assertSame($expected, $actual->getData(true));
    }

    public function testReplyNotExistsMessage()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Post reply message
            $arguments = [
                'replyToken' => '0f3779fba3b349968c5d07db31eab56f',
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => '我不大了解您的意思...',
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

        $expected = [
            'state' => true,
        ];

        $signature = 'KTeHk+28VBv5+rLcKfU3GJKESYo/zD7OpfxL1x1fcxE=';
        $content = '{"destination":"xxxxxxxxxx","events":[{"replyToken":"0f3779fba3b349968c5d07db31eab56f","type":"message","timestamp":1462629479859,"source":{"type":"user","userId":"UID12345678"},"message":{"id":"325708","type":"text","text":"這句話不存在"}}]}';
        $request = $this->createRequest($signature, $content);

        $response = $this->app->make(Response::class);

        $controller = new StubController();

        $actual = $controller->webhook($request, $response);

        $this->assertSame($expected, $actual->getData(true));
    }

    public function testThrowLineRequestErrorException()
    {
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

            $response = new LineResponse(400, '{"message":"The request body has 1 error","details":[{"message":"Throw the error","property":"messages[0].text"}]}');

            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/message/reply', $arguments)
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $expected = [
            'state' => false,
            'message' => '{"message":"The request body has 1 error","details":[{"message":"Throw the error","property":"messages[0].text"}]}',
        ];

        $signature = 'gsrBt6eFskfPn1MyDf8WsRjSArBECpAbW1Z9AbsBY5s=';
        $content = '{"destination":"xxxxxxxxxx","events":[{"replyToken":"0f3779fba3b349968c5d07db31eab56f","type":"message","timestamp":1462629479859,"source":{"type":"user","userId":"UID12345678"},"message":{"id":"325708","type":"text","text":"嗨"}}]}';
        $request = $this->createRequest($signature, $content);

        $response = $this->app->make(Response::class);

        $controller = new StubController();

        $actual = $controller->webhook($request, $response);

        $this->assertSame($expected, $actual->getData(true));
    }
}
