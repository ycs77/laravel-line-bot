<?php

namespace Ycs77\LaravelLineBot\Test\Message;

use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
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
    public function testAddButtonTemplateBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $action */
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

    public function testAddConfirmTemplateBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);
        $action->shouldReceive('message')
            ->with('Yes', null)
            ->once()
            ->andReturn(m::mock(MessageTemplateActionBuilder::class));
        $action->shouldReceive('message')
            ->with('No', null)
            ->once()
            ->andReturn(m::mock(MessageTemplateActionBuilder::class));

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);
        $bot->shouldReceive('action')
            ->once()
            ->andReturn($action);

        $template = new TemplateBuilder($bot);

        $template->confirm('text', function (ActionBuilder $action) {
            $action->message('Yes');
            $action->message('No');
        });

        $this->assertInstanceOf(ConfirmTemplateBuilder::class, $template->getTemplate());
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
