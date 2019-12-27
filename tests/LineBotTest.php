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
    /** @var \Ycs77\LaravelLineBot\LineBot */
    protected $bot;

    /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot */
    protected $baseLineBot;

    /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Config\Repository */
    protected $config;

    /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Illuminate\Contracts\Cache\Repository */
    protected $cache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseLineBot = m::mock(BaseLINEBot::class);
        $this->config = m::mock(Config::class);
        $this->cache = m::mock(Cache::class);
        $this->bot = $this->createBot();
    }

    public function tearDown(): void
    {
        $this->bot = null;
        $this->baseLineBot = null;
        $this->config = null;
        $this->cache = null;

        parent::tearDown();
    }

    public function createBot()
    {
        return new LineBot($this->baseLineBot, $this->config, $this->cache);
    }

    public function testReply()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\MessageBuilder\TextMessageBuilder $messageBuilder */
        $messageBuilder = m::mock(TextMessageBuilder::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Contracts\Response $response */
        $response = m::mock(Response::class);

        $this->baseLineBot->shouldReceive('replyMessage')
            ->with('token-123456', $messageBuilder)
            ->once()
            ->andReturn($response);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $event */
        $event = m::mock(BaseEvent::class);
        $event->shouldReceive('getReplyToken')
            ->once()
            ->andReturn('token-123456');

        $this->assertNull($this->bot->reply($messageBuilder));

        $this->bot->setEvent($event);

        $this->assertSame($response, $this->bot->reply($messageBuilder));
    }

    public function testNewBuilder()
    {
        $this->assertInstanceOf(Builder::class, $this->bot->query());
    }

    public function testNewAction()
    {
        $this->assertInstanceOf(Action::class, $this->bot->action());
    }

    public function testGetConfig()
    {
        $this->config->shouldReceive('get')
            ->with('key', null)
            ->once()
            ->andReturn('value...');

        $this->assertSame('value...', $this->bot->getConfig('key'));
        $this->assertSame($this->config, $this->bot->getConfig());
    }

    public function testGetAndSetEvent()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $event */
        $event = m::mock(BaseEvent::class);

        $this->assertNull($this->bot->getEvent($event));

        $this->bot->setEvent($event);

        $this->assertSame($event, $this->bot->getEvent($event));
    }

    public function testCallMagicMethodForQuery()
    {
        $quickReply = m::mock(QuickReplyBuilder::class);

        $this->assertInstanceOf(Builder::class, $this->bot->quickReply($quickReply));
    }

    public function testGetBaseBotInstance()
    {
        $this->assertSame($this->baseLineBot, $this->bot->base());
    }
}
