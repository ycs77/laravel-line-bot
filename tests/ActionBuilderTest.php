<?php

namespace Ycs77\LaravelLineBot\Test;

use LINE\LINEBot\TemplateActionBuilder;
use Mockery as m;
use Ycs77\LaravelLineBot\Action;
use Ycs77\LaravelLineBot\ActionBuilder;

class ActionBuilderTest extends TestCase
{
    public function testGetActions()
    {
        /** @var \Ycs77\LaravelLineBot\Action $action */
        $action = m::mock(Action::class);

        $quickReply = new ActionBuilder($action);

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

        $actionBuilder = new ActionBuilder($action);

        $actionBuilder->{$name}(...$arguments);

        $this->assertCount(1, $actionBuilder->get());
        $this->assertInstanceOf(TemplateActionBuilder::class, $actionBuilder->get()[0]);
    }
}
