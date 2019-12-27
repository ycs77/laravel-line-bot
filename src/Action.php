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
    /**
     * Create a new message action instance.
     *
     * @param  string  $label
     * @param  string|null  $text
     * @return \LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder
     */
    public function message(string $label, string $text = null)
    {
        return new MessageTemplateActionBuilder($label, $text ?? $label);
    }

    /**
     * Create a new URL action instance.
     *
     * @param  string  $label
     * @param  string  $uri
     * @param  string|null  $alt
     * @return \LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder
     */
    public function url(string $label, string $uri, string $alt = null)
    {
        $altUri = $alt ? new AltUriBuilder($alt) : null;

        return new UriTemplateActionBuilder($label, $uri, $altUri);
    }

    /**
     * Create a new postback action instance.
     *
     * @param  string  $label
     * @param  string  $data
     * @param  string|null  $displayText
     * @return \LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder
     */
    public function postback(string $label, string $data, string $displayText = null)
    {
        return new PostbackTemplateActionBuilder($label, $data, $displayText);
    }

    /**
     * Create a new location action instance.
     *
     * @param  string  $label
     * @return \LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder
     */
    public function location(string $label)
    {
        return new LocationTemplateActionBuilder($label);
    }

    /**
     * Create a new datetime picker action instance.
     *
     * @param  string  $label
     * @param  string  $data
     * @param  string  $mode
     * @param  string|null  $initial
     * @param  string|null  $max
     * @param  string|null  $min
     * @return \LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder
     */
    public function datetimePicker(string $label, string $data, string $mode, string $initial = null, string $max = null, string $min = null)
    {
        return new DatetimePickerTemplateActionBuilder($label, $data, $mode, $initial, $max, $min);
    }

    /**
     * Create a new camera action instance.
     *
     * @param  string  $label
     * @return \LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder
     */
    public function camera(string $label)
    {
        return new CameraTemplateActionBuilder($label);
    }

    /**
     * Create a new camera roll action instance.
     *
     * @param  string  $label
     * @return \LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder
     */
    public function cameraRoll(string $label)
    {
        return new CameraRollTemplateActionBuilder($label);
    }
}
