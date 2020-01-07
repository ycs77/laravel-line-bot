<?php

namespace Ycs77\LaravelLineBot\Test\Integration;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Response as LineResponse;
use Mockery as m;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Test\TestCase;
use Ycs77\LaravelLineBot\User as LineUser;

class LineBotTest extends TestCase
{
    /** @return \Ycs77\LaravelLineBot\LineBot */
    public function bot()
    {
        return $this->app['linebot'];
    }

    /** @return \Illuminate\Contracts\Cache\Repository */
    public function cache()
    {
        return $this->app['cache.store'];
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

    public function testGetProfileFromApi()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $baseEvent */
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('getUserId')
            ->once()
            ->andReturn('UID12345678');

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Contracts\Event $event */
        $event = m::mock(Event::class);
        $event->shouldReceive('base')
            ->once()
            ->andReturn($baseEvent);

        $expectedCacheUserContent = [
            'displayName' => 'Lucas',
            'userId' => 'UID12345678',
            'pictureUrl' => 'https://example.com/image/path...',
            'statusMessage' => 'Hello world!',
        ];

        $this->httpMock(function (MockInterface $mock) {
            // Get user profile
            $response = new LineResponse(200, '{"displayName":"Lucas","userId":"UID12345678","pictureUrl":"https://example.com/image/path...","statusMessage":"Hello world!"}');

            $mock->shouldReceive('get')
                ->with('https://api.line.me/v2/bot/profile/UID12345678')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->bot()->setEvent($event);

        $this->assertNull($this->cache()->get('linebot.profile.UID12345678'));

        $user = $this->bot()->profile();

        $this->assertInstanceOf(LineUser::class, $user);
        $this->assertSame('UID12345678', $user->id());
        $this->assertSame('Lucas', $user->name());
        $this->assertSame('https://example.com/image/path...', $user->picture());
        $this->assertSame('Hello world!', $user->status());
        $this->assertSame($expectedCacheUserContent, $this->cache()->get('linebot.profile.UID12345678'));
    }
}
