<?php

namespace Ycs77\LaravelLineBot\Test;

use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\QuickReplyBuilder;

class QuickReplyBuilderTest extends TestCase
{
    public function testGetActions()
    {
        /** @var \Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);

        $quickReply = new QuickReplyBuilder($action);

        $this->assertSame([], $quickReply->get());
    }

    public function actionsProvider()
    {
        return [
            'message' => [
                'message',
                ['label', 'text'],
            ],
            'url' => [
                'url',
                ['label', 'http://example.test/', 'alt'],
            ],
            'postback' => [
                'postback',
                ['label', '{"key":"data"}', 'displayText'],
            ],
            'location' => [
                'location',
                ['label'],
            ],
            'datetimePicker' => [
                'datetimePicker',
                ['label', '{"key":"data"}', 'datetime', null, null, null],
            ],
            'camera' => [
                'camera',
                ['label'],
            ],
            'cameraRoll' => [
                'cameraRoll',
                ['label'],
            ],
        ];
    }

    /**
     * @dataProvider actionsProvider
     */
    public function testAddAction($name, $arguments)
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\LINE\LINEBot\TemplateActionBuilder $templateActionBuilder */
        $templateActionBuilder = m::mock(TemplateActionBuilder::class);

        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);
        $action->shouldReceive($name)
            ->withArgs($arguments)
            ->once()
            ->andReturn($templateActionBuilder);

        $quickReply = new QuickReplyBuilder($action);

        $quickReply->{$name}(...$arguments);

        $this->assertCount(1, $quickReply->get());
        $this->assertInstanceOf(QuickReplyButtonBuilder::class, $quickReply->get()[0]);
    }

    public function testBuildQuickReplyMessageBuilder()
    {
        /** @var \Mockery\MockInterface|\Mockery\LegacyMockInterface|\Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);

        $quickReply = new QuickReplyBuilder($action);

        $this->assertInstanceOf(QuickReplyMessageBuilder::class, $quickReply->build());
    }
}
