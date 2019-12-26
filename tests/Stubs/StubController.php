<?php

namespace Ycs77\LaravelLineBot\Test\Stubs;

use Illuminate\Http\Request;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\Facades\LineBot;
use Ycs77\LaravelLineBot\LineBotService;

class StubController
{
    use LineBotService;

    public function webhook(Request $request, Response $response)
    {
        return $this->lineBotReply($request, $response);
    }

    protected function reply(array $events)
    {
        /** @var \LINE\LINEBot\Event\BaseEvent $event */
        foreach ($events as $event) {
            LineBot::setEvent($event);

            if ($event instanceof TextMessage) {
                switch ($event->getText()) {
                    case '嗨':
                        LineBot::text('你好')->reply();
                        break;

                    default:
                        $this->fallback($event);
                }
            }
        }
    }

    protected function fallback(BaseEvent $event)
    {
        LineBot::text('我不大了解您的意思...')->reply();
    }
}
