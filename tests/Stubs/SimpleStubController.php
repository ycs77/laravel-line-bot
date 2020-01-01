<?php

namespace Ycs77\LaravelLineBot\Test\Stubs;

use Illuminate\Http\Request;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\LineBotService;

class SimpleStubController
{
    use LineBotService;

    public function webhook(Request $request, Response $response)
    {
        return $this->lineBotReply($request, $response);
    }
}
