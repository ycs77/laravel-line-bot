<?php

namespace Ycs77\LaravelLineBot\Test\Stubs\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\Facades\LineBot;
use Ycs77\LaravelLineBot\LineBotService;

class BaseConversationController
{
    use LineBotService;

    public function webhook(Request $request, Response $response)
    {
        return $this->lineBotReply($request, $response);
    }

    protected function reply(array $events)
    {
        LineBot::routes($events, function () {
            LineBot::on()->text('問答開始', function () {
                LineBot::text('請問你的名字是?')->reply();
                Cache::put('linebot.conversation.test', [], now()->addMinutes(120));
            });

            if (Cache::has('linebot.conversation.test')) {
                $data = Cache::get('linebot.conversation.test');

                if (!array_key_exists('name', $data)) {
                    LineBot::on()->text('{name}', function ($name) use ($data) {
                        $data['name'] = $name;
                        Cache::put('linebot.conversation.test', $data, now()->addMinutes(120));

                        LineBot::text('請問你的年齡是?')->reply();
                    });
                }
                if (!array_key_exists('age', $data)) {
                    LineBot::on()->text('{age}', function ($age) use ($data) {
                        $data['age'] = $age;
                        Cache::forget('linebot.conversation.test');

                        LineBot::text("你好{$data['name']}，今年{$data['age']}歲")->reply();
                    });
                }
            }
        });
    }
}
