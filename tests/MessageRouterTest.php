<?php

namespace Ycs77\LaravelLineBot\Test;

use Mockery as m;
use Ycs77\LaravelLineBot\Event\AccountLinkEvent;
use Ycs77\LaravelLineBot\Event\AudioEvent;
use Ycs77\LaravelLineBot\Event\FallbackEvent;
use Ycs77\LaravelLineBot\Event\FileEvent;
use Ycs77\LaravelLineBot\Event\FollowEvent;
use Ycs77\LaravelLineBot\Event\ImageEvent;
use Ycs77\LaravelLineBot\Event\JoinEvent;
use Ycs77\LaravelLineBot\Event\LeaveEvent;
use Ycs77\LaravelLineBot\Event\LocationEvent;
use Ycs77\LaravelLineBot\Event\MemberJoinEvent;
use Ycs77\LaravelLineBot\Event\MemberLeaveEvent;
use Ycs77\LaravelLineBot\Event\PostbackEvent;
use Ycs77\LaravelLineBot\Event\StickerEvent;
use Ycs77\LaravelLineBot\Event\TextEvent;
use Ycs77\LaravelLineBot\Event\UnfollowEvent;
use Ycs77\LaravelLineBot\Event\VideoEvent;
use Ycs77\LaravelLineBot\Incoming\Collection;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\MessageRouter;

class MessageRouterTest extends TestCase
{
    /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot */
    protected $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bot = m::mock(LineBot::class);
    }

    public function eventsProvider()
    {
        return [
            'text'             => ['text', TextEvent::class, ['文字']],
            'text has pattern' => ['text', TextEvent::class, ['我叫{name}']],
            'image'            => ['image', ImageEvent::class],
            'video'            => ['video', VideoEvent::class],
            'audio'            => ['audio', AudioEvent::class],
            'file'             => ['file', FileEvent::class],
            'location'         => ['location', LocationEvent::class],
            'sticker'          => ['sticker', StickerEvent::class],
            'follow'           => ['follow', FollowEvent::class],
            'unfollow'         => ['unfollow', UnfollowEvent::class],
            'join'             => ['join', JoinEvent::class],
            'leave'            => ['leave', LeaveEvent::class],
            'memberJoin'       => ['memberJoin', MemberJoinEvent::class],
            'memberLeave'      => ['memberLeave', MemberLeaveEvent::class],
            'postback'         => ['postback', PostbackEvent::class],
            'accountLink'      => ['accountLink', AccountLinkEvent::class],
            'fallback'         => ['fallback', FallbackEvent::class],
        ];
    }

    /**
     * @dataProvider eventsProvider
     */
    public function testAddEvent($name, $eventClass, $values = [])
    {
        $event = m::mock($eventClass);

        $this->bot->shouldReceive('getEvent')
            ->once()
            ->andReturn($event);

        $messageRouter = new MessageRouter($this->bot);

        $parameter = array_merge($values, [function () {
            //
        }]);

        $this->assertCount(0, $messageRouter->getMessages());

        $messageRouter->{$name}(...$parameter);

        $this->assertCount(1, $messageRouter->getMessages());
        /** @var \Ycs77\LaravelLineBot\Incoming\IncomingMessage $incomingMessage */
        $incomingMessage = $messageRouter->getMessages()->all()[0];
        $this->assertInstanceOf($incomingMessage->getExpectEventClass(), $event);
    }

    public function testGetMessages()
    {
        $messageRouter = new MessageRouter($this->bot);

        $this->assertInstanceOf(Collection::class, $messageRouter->getMessages());
    }
}
