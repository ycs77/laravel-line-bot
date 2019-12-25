<?php

namespace Ycs77\LaravelLineBot;

use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\Uri\AltUriBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class Action
{
    public function message(string $label, string $text = null)
    {
        return new MessageTemplateActionBuilder($label, $text ?? $label);
    }

    public function url(string $label, string $uri, string $alt = null)
    {
        $altUri = $alt ? new AltUriBuilder($alt) : null;

        return new UriTemplateActionBuilder($label, $uri, $altUri);
    }

    public function postback(string $label, string $data, string $displayText = null)
    {
        return new PostbackTemplateActionBuilder($label, $data, $displayText);
    }

    public function location(string $label)
    {
        return new LocationTemplateActionBuilder($label);
    }

    public function datetimePicker(string $label, string $data, string $mode, string $initial = null, string $max = null, string $min = null)
    {
        return new DatetimePickerTemplateActionBuilder($label, $data, $mode, $initial, $max, $min);
    }

    public function camera(string $label)
    {
        return new CameraTemplateActionBuilder($label);
    }

    public function cameraRoll(string $label)
    {
        return new CameraRollTemplateActionBuilder($label);
    }
}
