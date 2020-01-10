<?php

namespace Ycs77\LaravelLineBot\Test\Stubs\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Ycs77\LaravelLineBot\ActionBuilder;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\Facades\LineBot;
use Ycs77\LaravelLineBot\LineBotService;
use Ycs77\LaravelLineBot\Message\TemplateBuilder;

class BranchConversationController
{
    use LineBotService;

    public function webhook(Request $request, Response $response)
    {
        return $this->lineBotReply($request, $response);
    }

    protected function reply(array $events)
    {
        LineBot::routes($events, function () {
            LineBot::on()->text('分支問答開始', function () {
                LineBot::text('請問你的名字是?')->reply();
                Cache::put('linebot.conversation.branch', [], now()->addMinutes(120));
            });

            if (Cache::has('linebot.conversation.branch')) {
                $data = Cache::get('linebot.conversation.branch');

                if (!array_key_exists('name', $data)) {
                    LineBot::on()->text('{name}', function ($name) use ($data) {
                        $data['name'] = $name;
                        Cache::put('linebot.conversation.branch', $data, now()->addMinutes(120));

                        LineBot::template('選擇性別選單', function (TemplateBuilder $template) {
                            $template->confirm('請問你的性別是', function (ActionBuilder $action) {
                                $action->message('男');
                                $action->message('女');
                            });
                        })->reply();
                    });
                }
                if (!array_key_exists('gender', $data)) {
                    LineBot::on()->text('{gender}', function ($gender) use ($data) {
                        $data['gender'] = $gender;
                        Cache::forget('linebot.conversation.branch');

                        if ($data['gender'] === '男') {
                            LineBot::text("你好{$data['name']}先生")->reply();
                        } elseif ($data['gender'] === '女') {
                            LineBot::text("你好{$data['name']}小姐")->reply();
                        } else {
                            LineBot::text("你好{$data['name']}")->reply();
                        }
                    });
                }
            }
        });
    }
}
