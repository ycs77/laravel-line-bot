<?php

namespace Ycs77\LaravelLineBot\Test\Commands;

use LINE\LINEBot\Response;
use Mockery\MockInterface;
use Ycs77\LaravelLineBot\Test\TestCase;

class CreateLineBotRichMenuCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('linebot.rich_menus', [
            'rich_menu_1' => [
                'size' => [
                    'width' => 2500,
                    'height' => 1686,
                ],
                'selected' => false,
                'name' => 'Nice richmenu',
                'chatBarText' => 'Tap here',
                'areas' => [
                    [
                        'bounds' => [
                            'x' => 0,
                            'y' => 0,
                            'width' => 2500,
                            'height' => 1686,
                        ],
                        'action' => [
                            'type' => 'message',
                            'label' => 'Message',
                            'text' => 'Message',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testCreateLineBotRichMenu()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Create rich menu
            $response = new Response(200, '{"richMenuId":"richmenu-1234567890"}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api.line.me/v2/bot/richmenu',
                    $this->app['config']->get('linebot.rich_menus')['rich_menu_1']
                )
                ->once()
                ->andReturn($response);

            // Upload rich menu image
            $response = new Response(200, '{}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api-data.line.me/v2/bot/richmenu/richmenu-1234567890/content',
                    [
                        '__file' => 'public/images/image.jpg',
                        '__type' => 'image/jpeg',
                    ],
                    ['Content-Type: image/jpeg']
                )
                ->once()
                ->andReturn($response);

            // Create rich menu
            $response = new Response(200, '{}');
            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/user/all/richmenu/richmenu-1234567890', [])
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:create', [
            'name' => 'rich_menu_1',
            'image' => 'public/images/image.jpg',
        ])
            ->expectsOutput('Create the Line Bot rich menu is successfully.')
            ->assertExitCode(0);
    }

    public function testCreateRichMenuFail()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Create rich menu
            $response = new Response(400, '{"message":"The request body has 1 error"}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api.line.me/v2/bot/richmenu',
                    $this->app['config']->get('linebot.rich_menus')['rich_menu_1']
                )
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:create', [
            'name' => 'rich_menu_1',
            'image' => 'public/images/image.jpg',
        ])
            ->assertExitCode(0);
    }

    public function testCreateRichMenuNameNotExists()
    {
        $this->artisan('linebot:richmenu:create', [
            'name' => 'not_exists_rich_menu',
            'image' => 'public/images/image.jpg',
        ])
            ->expectsOutput('The Rich Menu name is not exists.')
            ->assertExitCode(0);
    }

    public function testUploadRichMenuImageFail()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Create rich menu
            $response = new Response(200, '{"richMenuId":"richmenu-1234567890"}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api.line.me/v2/bot/richmenu',
                    $this->app['config']->get('linebot.rich_menus')['rich_menu_1']
                )
                ->once()
                ->andReturn($response);

            // Upload rich menu image
            $response = new Response(400, '{"message":"The request body has 1 error"}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api-data.line.me/v2/bot/richmenu/richmenu-1234567890/content',
                    [
                        '__file' => 'public/images/image.jpg',
                        '__type' => 'image/jpeg',
                    ],
                    ['Content-Type: image/jpeg']
                )
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:create', [
            'name' => 'rich_menu_1',
            'image' => 'public/images/image.jpg',
        ])
            ->assertExitCode(0);
    }

    public function testLinkRichMenuFail()
    {
        $this->httpMock(function (MockInterface $mock) {
            // Create rich menu
            $response = new Response(200, '{"richMenuId":"richmenu-1234567890"}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api.line.me/v2/bot/richmenu',
                    $this->app['config']->get('linebot.rich_menus')['rich_menu_1']
                )
                ->once()
                ->andReturn($response);

            // Upload rich menu image
            $response = new Response(200, '{}');
            $mock->shouldReceive('post')
                ->with(
                    'https://api-data.line.me/v2/bot/richmenu/richmenu-1234567890/content',
                    [
                        '__file' => 'public/images/image.jpg',
                        '__type' => 'image/jpeg',
                    ],
                    ['Content-Type: image/jpeg']
                )
                ->once()
                ->andReturn($response);

            // Create rich menu
            $response = new Response(400, '{"message":"The request body has 1 error"}');
            $mock->shouldReceive('post')
                ->with('https://api.line.me/v2/bot/user/all/richmenu/richmenu-1234567890', [])
                ->once()
                ->andReturn($response);

            return $mock;
        });

        $this->artisan('linebot:richmenu:create', [
            'name' => 'rich_menu_1',
            'image' => 'public/images/image.jpg',
        ])
            ->assertExitCode(0);
    }
}
