<?php

namespace Ycs77\LaravelLineBot\Test\Message;

use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\ActionBuilder;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\Message\Template;
use Ycs77\LaravelLineBot\Message\TemplateBuilder;
use Ycs77\LaravelLineBot\Test\TestCase;

class TemplateTest extends TestCase
{
    public function testNewTemplateAndAltTextIsText()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);

        new Template($bot, 'altText', function (TemplateBuilder $template) {
            //
        });
    }

    public function testNewTemplateAndAltTextFromTemplateMessageBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\LineBot $bot */
        $bot = m::mock(LineBot::class);

        $templateMessageBuilder = m::mock(TemplateMessageBuilder::class);

        $template = new Template($bot, $templateMessageBuilder, function (TemplateBuilder $template) {
            //
        });

        $this->assertSame($templateMessageBuilder, $template->getMessageBuilder());
    }

    public function testGetMessageBuilder()
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

        $template = new Template($bot, 'altText', function (TemplateBuilder $template) {
            $template->button(null, 'text', null, function (ActionBuilder $action) {
                $action->url(
                    'Laravel line bot Github',
                    'https://github.com/ycs77/laravel-line-bot'
                );
            });
        });

        $this->assertInstanceOf(TemplateMessageBuilder::class, $template->getMessageBuilder());
    }
}
