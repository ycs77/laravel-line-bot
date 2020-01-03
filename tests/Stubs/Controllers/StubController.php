<?php

namespace Ycs77\LaravelLineBot\Test\Stubs\Controllers;

use Illuminate\Http\Request;
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
        LineBot::routes($events, function () {
            LineBot::on()->text('哈囉', function () {
                LineBot::text('你好')->reply();
            });

            LineBot::on()->fallback(function () {
                LineBot::text('我不大了解您的意思...')->reply();
            });
        });
    }
}
