<?php

namespace Ycs77\LaravelLineBot\Event;

use LINE\LINEBot\Event\AccountLinkEvent as BaseAccountLinkEvent;
use LINE\LINEBot\Event\FollowEvent as BaseFollowEvent;
use LINE\LINEBot\Event\JoinEvent as BaseJoinEvent;
use LINE\LINEBot\Event\LeaveEvent as BaseLeaveEvent;
use LINE\LINEBot\Event\MemberJoinEvent as BaseMemberJoinEvent;
use LINE\LINEBot\Event\MemberLeaveEvent as BaseMemberLeaveEvent;
use LINE\LINEBot\Event\MessageEvent\AudioMessage;
use LINE\LINEBot\Event\MessageEvent\FileMessage;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\LocationMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\MessageEvent\VideoMessage;
use LINE\LINEBot\Event\PostbackEvent as BasePostbackEvent;
use LINE\LINEBot\Event\UnfollowEvent as BaseUnfollowEvent;

class Transformer
{
    /**
     * The event class mapping array.
     *
     * @var array
     */
    public static $mapping = [
        TextMessage::class => TextEvent::class,
        ImageMessage::class => ImageEvent::class,
        VideoMessage::class => VideoEvent::class,
        AudioMessage::class => AudioEvent::class,
        FileMessage::class => FileEvent::class,
        LocationMessage::class => LocationEvent::class,
        StickerMessage::class => StickerEvent::class,
        BaseFollowEvent::class => FollowEvent::class,
        BaseUnfollowEvent::class => UnfollowEvent::class,
        BaseJoinEvent::class => JoinEvent::class,
        BaseLeaveEvent::class => LeaveEvent::class,
        BaseMemberJoinEvent::class => MemberJoinEvent::class,
        BaseMemberLeaveEvent::class => MemberLeaveEvent::class,
        BasePostbackEvent::class => PostbackEvent::class,
        BaseAccountLinkEvent::class => AccountLinkEvent::class,
    ];

    /**
     * Handle mapping events.
     *
     * @param  array  $events
     * @return array
     */
    public static function handle(array $events)
    {
        return array_map(function ($event) {
            if ($eventClassName = static::getNewEventClassName($event)) {
                return new $eventClassName($event);
            }

            return $event;
        }, $events);
    }

    /**
     * Get the mapping event class name.
     *
     * @param  mixed  $baseEvent
     * @return string|null
     */
    public static function getNewEventClassName($baseEvent)
    {
        $callback = function ($eventClass, $baseEventClass) use ($baseEvent) {
            return $baseEvent instanceof $baseEventClass;
        };

        $events = array_values(
            array_filter(
                static::$mapping,
                $callback,
                ARRAY_FILTER_USE_BOTH
            )
        );

        return $events[0] ?? null;
    }
}
