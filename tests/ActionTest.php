<?php

namespace Ycs77\LaravelLineBot\Test;

use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use Ycs77\LaravelLineBot\Action;

class ActionTest extends TestCase
{
    public function actionsProvider()
    {
        return [
            'message' => [
                'message',
                ['label', 'text'],
                MessageTemplateActionBuilder::class,
            ],
            'url' => [
                'url',
                ['label', 'http://example.test/', 'alt'],
                UriTemplateActionBuilder::class,
            ],
            'postback' => [
                'postback',
                ['label', '{"key":"data"}', 'displayText'],
                PostbackTemplateActionBuilder::class,
            ],
            'location' => [
                'location',
                ['label'],
                LocationTemplateActionBuilder::class,
            ],
            'datetimePicker' => [
                'datetimePicker',
                ['label', '{"key":"data"}', 'datetime', null, null, null],
                DatetimePickerTemplateActionBuilder::class,
            ],
            'camera' => [
                'camera',
                ['label'],
                CameraTemplateActionBuilder::class,
            ],
            'cameraRoll' => [
                'cameraRoll',
                ['label'],
                CameraRollTemplateActionBuilder::class,
            ],
        ];
    }

    /**
     * @dataProvider actionsProvider
     */
    public function testNewAction($name, $arguments, $expectedClass)
    {
        $action = new Action();

        $actionBuilder = $action->{$name}(...$arguments);

        $this->assertInstanceOf($expectedClass, $actionBuilder);
    }
}
