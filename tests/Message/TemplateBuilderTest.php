<?php

namespace Ycs77\LaravelLineBot\Test\Message;

use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\ActionBuilder;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\Message\TemplateBuilder;
use Ycs77\LaravelLineBot\Test\TestCase;

class TemplateBuilderTest extends TestCase
{
    public function testAddButtonMessageBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $bot */
        $action = m::mock(Action::class);
        $action->shouldReceive('url')
            ->with('Laravel line bot Github', 'https://github.com/ycs77/laravel-line-bot', null)
            ->once()
            ->andReturn(m::mock(UriTemplateActionBuilder::class));

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('action')
            ->once()
            ->andReturn($action);

        $template = new TemplateBuilder($bot);

        $template->button(null, 'text', null, function (ActionBuilder $action) {
            $action->url(
                'Laravel line bot Github',
                'https://github.com/ycs77/laravel-line-bot'
            );
        });

        $this->assertInstanceOf(ButtonTemplateBuilder::class, $template->getTemplate());
    }

    public function testGetTemplateThrowException()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $bot */
        $action = m::mock(Action::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('action')
            ->once()
            ->andReturn($action);

        $template = new TemplateBuilder($bot);

        $this->expectException(LineRequestErrorException::class);
        $this->expectExceptionMessage('The template builder message must implements LINE\LINEBot\MessageBuilder\TemplateBuilder interface');

        $template->getTemplate();
    }
}
