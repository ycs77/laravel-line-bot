<?php

namespace Ycs77\LaravelLineBot\Test\Commands;

use LINE\LINEBot\Response;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Test\TestCase;

class GetLineBotRichMenuListCommandTest extends TestCase
{
    public function testGetLineBotRichMenuListFormatIds()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Get rich menu list
            $response = new Response(200, '{"richmenus":[{"richMenuId":"richmenu-1","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]},{"richMenuId":"richmenu-2","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]}]}');
            $mock->shouldReceive('get')
                ->with('https://api.line.me/v2/bot/richmenu/list')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:list')
            ->expectsOutput('richmenu-1')
            ->expectsOutput('richmenu-2')
            ->assertExitCode(0);
    }

    public function testGetLineBotRichMenuListReturnEmpty()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Get rich menu list
            $response = new Response(200, '{"richmenus":[]}');
            $mock->shouldReceive('get')
                ->with('https://api.line.me/v2/bot/richmenu/list')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:list')
            ->expectsOutput('The rich menu is empty.')
            ->assertExitCode(0);
    }

    public function testGetLineBotRichMenuListRawData()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Get rich menu list
            $response = new Response(200, '{"richmenus":[{"richMenuId":"richmenu-1","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]},{"richMenuId":"richmenu-2","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]}]}');
            $mock->shouldReceive('get')
                ->with('https://api.line.me/v2/bot/richmenu/list')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:list', ['--raw' => true])
            ->expectsOutput('{"richmenus":[{"richMenuId":"richmenu-1","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]},{"richMenuId":"richmenu-2","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]}]}')
            ->assertExitCode(0);
    }

    public function testGetLineBotRichMenuListFail()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Get rich menu list
            $response = new Response(400, '{"message":"The request body has 1 error"}');
            $mock->shouldReceive('get')
                ->with('https://api.line.me/v2/bot/richmenu/list')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:list')
            ->assertExitCode(0);
    }
}
