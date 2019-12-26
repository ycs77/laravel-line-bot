<?php

namespace Ycs77\LaravelLineBot\Test;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\Message\Builder;
use Ycs77\LaravelLineBot\QuickReplyBuilder;

class LineBotTest extends TestCase
{
    public function testReply()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\MessageBuilder\TextMessageBuilder $messageBuilder */
        $messageBuilder = m::mock(TextMessageBuilder::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Contracts\Response $response */
        $response = m::mock(Response::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);
        $baseLineBot->shouldReceive('replyMessage')
            ->with('token-123456', $messageBuilder)
            ->once()
            ->andReturn($response);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $event */
        $event = m::mock(BaseEvent::class);
        $event->shouldReceive('getReplyToken')
            ->once()
            ->andReturn('token-123456');

        $bot = new LineBot($baseLineBot, $config, $cache);

        $this->assertNull($bot->reply($messageBuilder));

        $bot->setEvent($event);

        $this->assertSame($response, $bot->reply($messageBuilder));
    }

    public function testNewBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        $bot = new LineBot($baseLineBot, $config, $cache);

        $this->assertInstanceOf(Builder::class, $bot->query());
    }

    public function testNewAction()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        $bot = new LineBot($baseLineBot, $config, $cache);

        $this->assertInstanceOf(Action::class, $bot->action());
    }

    public function testGetConfig()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);
        $config->shouldReceive('get')
            ->with('key', null)
            ->once()
            ->andReturn('value...');

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        $bot = new LineBot($baseLineBot, $config, $cache);

        $this->assertSame('value...', $bot->getConfig('key'));
        $this->assertSame($config, $bot->getConfig());
    }

    public function testGetAndSetEvent()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $event */
        $event = m::mock(BaseEvent::class);

        $bot = new LineBot($baseLineBot, $config, $cache);

        $this->assertNull($bot->getEvent($event));

        $bot->setEvent($event);

        $this->assertSame($event, $bot->getEvent($event));
    }

    public function testCallMagicMethodForQuery()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        $bot = new LineBot($baseLineBot, $config, $cache);

        $quickReply = m::mock(QuickReplyBuilder::class);

        $this->assertInstanceOf(Builder::class, $bot->quickReply($quickReply));
    }

    public function testGetBaseBotInstance()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot $baseLineBot */
        $baseLineBot = m::mock(BaseLINEBot::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository $config */
        $config = m::mock(Config::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository $cache */
        $cache = m::mock(Cache::class);

        $bot = new LineBot($baseLineBot, $config, $cache);

        $this->assertSame($baseLineBot, $bot->base());
    }
}
