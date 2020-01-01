<?php

namespace Ycs77\LaravelLineBot\Test\Commands;

use LINE\LINEBot\Response;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Test\TestCase;

class ClearLineBotRichMenuCommandTest extends TestCase
{
    public function testDeleteLineBotRichMenuForIds()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Delete rich menu 1
            $response = new Response(200, '{}');
            $mock->shouldReceive('delete')
                ->with('https://api.line.me/v2/bot/richmenu/richmenu-1')
                ->once()
                ->andReturn($response);

            // Delete rich menu 2
            $response = new Response(200, '{}');
            $mock->shouldReceive('delete')
                ->with('https://api.line.me/v2/bot/richmenu/richmenu-2')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:clear', [
            'id' => ['richmenu-1', 'richmenu-2'],
        ])
            ->expectsOutput('Clear the Line Bot rich menu is successfully.')
            ->assertExitCode(0);
    }

    public function testDeleteAllLineBotRichMenu()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Get rich menu list
            $response = new Response(200, '{"richmenus":[{"richMenuId":"richmenu-1","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]},{"richMenuId":"richmenu-2","size":{"width":2500,"height":1686},"selected":false,"areas":[{"bounds":{"x":0,"y":0,"width":2500,"height":1686},"action":{"type":"postback","data":"action=buy&itemid=123"}}]}]}');
            $mock->shouldReceive('get')
                ->with('https://api.line.me/v2/bot/richmenu/list')
                ->once()
                ->andReturn($response);

            // Delete rich menu 1
            $response = new Response(200, '{}');
            $mock->shouldReceive('delete')
                ->with('https://api.line.me/v2/bot/richmenu/richmenu-1')
                ->once()
                ->andReturn($response);

            // Delete rich menu 2
            $response = new Response(200, '{}');
            $mock->shouldReceive('delete')
                ->with('https://api.line.me/v2/bot/richmenu/richmenu-2')
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:clear', ['--all' => true])
            ->expectsOutput('Clear the Line Bot rich menu is successfully.')
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

        $this->artisan('linebot:richmenu:clear', ['--all' => true])
            ->assertExitCode(0);
    }

    public function testDeleteLineBotRichMenuNoSetIds()
    {
        $this->artisan('linebot:richmenu:clear')
            ->expectsOutput('The id is fail.')
            ->assertExitCode(0);
    }
}
