<?php

namespace Ycs77\LaravelLineBot\Test;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use LINE\LINEBot as BaseLINEBot;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Response as LineResponse;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;
use Ycs77\LaravelLineBot\File\Factory as FileFactory;
use Ycs77\LaravelLineBot\Incoming\Collection as IncomingCollection;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\Matching\MatchedMessage;
use Ycs77\LaravelLineBot\Matching\Matcher;
use Ycs77\LaravelLineBot\Message\Builder;
use Ycs77\LaravelLineBot\MessageRouter;
use Ycs77\LaravelLineBot\Profile;
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

    /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\MessageRouter */
    protected $router;

    /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Matching\Matcher */
    protected $matcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseLineBot = m::mock(BaseLINEBot::class);
        $this->config = m::mock(Config::class);
        $this->cache = m::mock(Cache::class);
        $this->router = m::mock(MessageRouter::class);
        $this->matcher = m::mock(Matcher::class);
        $this->bot = $this->createBot();
    }

    public function tearDown(): void
    {
        $this->bot = null;
        $this->baseLineBot = null;
        $this->config = null;
        $this->cache = null;
        $this->router = null;
        $this->matcher = null;

        parent::tearDown();
    }

    public function createBot()
    {
        $bot = new LineBot($this->baseLineBot, $this->config, $this->cache);
        $bot->setRouter($this->router);
        $bot->setMatcher($this->matcher);

        return $bot;
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

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\Event\BaseEvent $baseEvent */
        $baseEvent = m::mock(BaseEvent::class);
        $baseEvent->shouldReceive('getReplyToken')
            ->once()
            ->andReturn('token-123456');

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Contracts\Event $event */
        $event = m::mock(Event::class);
        $event->shouldReceive('base')
            ->once()
            ->andReturn($baseEvent);

        $this->assertNull($this->bot->reply($messageBuilder));

        $this->bot->setEvent($event);

        $this->assertSame($response, $this->bot->reply($messageBuilder));
    }

    public function testGetMessageFile()
    {
        Storage::fake();

        $response = new LineResponse(200, 'content...');

        $this->baseLineBot->shouldReceive('getMessageContent')
            ->with('123456')
            ->once()
            ->andReturn($response);

        $file = $this->bot->file('123456');

        $this->assertInstanceOf(File::class, $file);
        Storage::assertExists('linebot/' . $file->getFilename());
    }

    public function testGetMessageFileReturnFail()
    {
        $response = new LineResponse(400, 'error...');

        $this->baseLineBot->shouldReceive('getMessageContent')
            ->with('123456')
            ->once()
            ->andReturn($response);

        $this->expectException(LineRequestErrorException::class);
        $this->expectExceptionMessage('Error with getting LineBot message content');

        $this->bot->file('123456');
    }

    public function testNewMessageBuilder()
    {
        $this->assertInstanceOf(Builder::class, $this->bot->say());
    }

    public function testNewAction()
    {
        $this->assertInstanceOf(Action::class, $this->bot->action());
    }

    public function testGetAndSetRouter()
    {
        $router = m::mock(MessageRouter::class);

        $this->bot->setRouter($router);

        $this->assertSame($router, $this->bot->getRouter());

        $this->bot->setRouter(function ($bot) use ($router) {
            return $router;
        });

        $this->assertSame($router, $this->bot->getRouter());

        // Alias
        $this->assertSame($router, $this->bot->on());
    }

    public function testGetAndSetMatcher()
    {
        /** @var \Ycs77\LaravelLineBot\Matching\Matcher $matcher */
        $matcher = m::mock(Matcher::class);

        $this->bot->setMatcher($matcher);

        $this->assertSame($matcher, $this->bot->getMatcher());
    }

    public function testGetAndSetFileFactory()
    {
        /** @var \Ycs77\LaravelLineBot\File\Factory $fileFactory */
        $fileFactory = m::mock(FileFactory::class);

        $this->bot->setFileFactory($fileFactory);

        $this->assertSame($fileFactory, $this->bot->getFileFactory());
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
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Contracts\Event $event */
        $event = m::mock(Event::class);

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

    public function testRoutesCallMatchedMessageCallback()
    {
        $replyCallback = function () {
            //
        };

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = m::mock(IncomingMessage::class);
        $incomingMessage->shouldReceive('getReplyCallback')
            ->once()
            ->andReturn($replyCallback);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Matching\MatchedMessage $matchedMessage */
        $matchedMessage = m::mock(MatchedMessage::class);
        $matchedMessage->shouldReceive('getMessage')
            ->once()
            ->andReturn($incomingMessage);

        $event = m::mock(TextEvent::class);
        $event->shouldReceive('getParameters')
            ->with($matchedMessage)
            ->once()
            ->andReturn(['小明']);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\Collection $incomingMessages */
        $incomingMessages = m::mock(IncomingCollection::class);

        $events = [$event];

        $this->router->shouldReceive('getMessages')
            ->once()
            ->andReturn($incomingMessages);

        $this->matcher->shouldReceive('match')
            ->with($incomingMessages)
            ->once()
            ->andReturn($matchedMessage);

        $this->bot->routes($events, function () {
            // register routes...
        });

        $this->assertSame($event, $this->bot->getEvent());
    }

    public function testRoutesCallFallbackMessageCallback()
    {
        $replyCallback = function () {
            //
        };

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\IncomingMessage $fallbackMessage */
        $fallbackMessage = m::mock(IncomingMessage::class);
        $fallbackMessage->shouldReceive('getReplyCallback')
            ->once()
            ->andReturn($replyCallback);

        $event = m::mock(TextEvent::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Incoming\Collection $incomingCollection */
        $incomingCollection = m::mock(IncomingCollection::class);
        $incomingCollection->shouldReceive('getFallback')
            ->once()
            ->andReturn($fallbackMessage);

        $events = [$event];

        $this->router->shouldReceive('getMessages')
            ->once()
            ->andReturn($incomingCollection);

        $this->matcher->shouldReceive('match')
            ->with($incomingCollection)
            ->once()
            ->andReturn(null);

        $this->bot->routes($events, function () {
            // register routes...
        });

        $this->assertSame($event, $this->bot->getEvent());
    }

    public function testGetMessageRouter()
    {
        $this->assertInstanceOf(MessageRouter::class, $this->bot->on());
    }

    public function testGetProfileFromApi()
    {
        Carbon::setTestNow(Carbon::create(2020, 1, 1, 0, 0, 0));

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

        $this->config->shouldReceive('get')
            ->with('linebot.cache_ttl', 120)
            ->once()
            ->andReturn(120);

        $this->cache->shouldReceive('get')
            ->with('linebot.profile.UID12345678')
            ->once()
            ->andReturn(null);
        $this->cache->shouldReceive('put')
            ->with(
                'linebot.profile.UID12345678',
                $expectedCacheUserContent,
                m::on(function ($argument) {
                    return $argument->equalTo(Carbon::create(2020, 1, 1, 2, 0, 0));
                })
            )
            ->once();

        $response = new LineResponse(200, '{"displayName":"Lucas","userId":"UID12345678","pictureUrl":"https://example.com/image/path...","statusMessage":"Hello world!"}');

        $this->baseLineBot->shouldReceive('getProfile')
            ->with('UID12345678')
            ->once()
            ->andReturn($response);

        $this->bot->setEvent($event);

        $profile = $this->bot->profile();

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertSame('UID12345678', $profile->id());
        $this->assertSame('Lucas', $profile->name());
        $this->assertSame('https://example.com/image/path...', $profile->picture());
        $this->assertSame('Hello world!', $profile->status());
    }

    public function testGetProfileFromCache()
    {
        Carbon::setTestNow('2020/01/01 00:00:00');

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

        $this->config->shouldReceive('get')
            ->never();

        $this->cache->shouldReceive('get')
            ->with('linebot.profile.UID12345678')
            ->once()
            ->andReturn($expectedCacheUserContent);
        $this->cache->shouldReceive('put')
            ->never();

        $this->bot->setEvent($event);

        $profile = $this->bot->profile();

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertSame('UID12345678', $profile->id());
        $this->assertSame('Lucas', $profile->name());
        $this->assertSame('https://example.com/image/path...', $profile->picture());
        $this->assertSame('Hello world!', $profile->status());
    }

    public function testGetProfileThrowMissingEventError()
    {
        $this->expectException(LineRequestErrorException::class);
        $this->expectExceptionMessage('The LineBot event missing');

        $this->bot->profile();
    }

    public function testGetProfileFromApiFail()
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

        $this->cache->shouldReceive('get')
            ->with('linebot.profile.UID12345678')
            ->once()
            ->andReturn(null);

        $response = new LineResponse(400, '{}');

        $this->baseLineBot->shouldReceive('getProfile')
            ->with('UID12345678')
            ->once()
            ->andReturn($response);

        $this->bot->setEvent($event);

        $this->expectException(LineRequestErrorException::class);
        $this->expectExceptionMessage('Error with getting Line profile');

        $this->bot->profile();
    }
}
