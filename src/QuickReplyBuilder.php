<?php

namespace Ycs77\LaravelLineBot;

use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;

class QuickReplyBuilder
{
    /**
     * The action factory instance.
     *
     * @var \Ycs77\LaravelLineBot\Action
     */
    protected $action;

    /**
     * The action instances.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Create a new action builder instance.
     *
     * @param  \Ycs77\LaravelLineBot\Action  $action
     * @return void
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
    }

    /**
     * Get all actions.
     *
     * @return array
     */
    public function get()
    {
        return $this->actions;
    }

    /**
     * Add a new action button.
     *
     * @param  \LINE\LINEBot\TemplateActionBuilder  $actionBuilder
     * @param  string|null  $imageUrl
     * @return self
     */
    public function add(TemplateActionBuilder $actionBuilder, string $imageUrl = null)
    {
        $this->actions[] = new QuickReplyButtonBuilder($actionBuilder, $imageUrl);

        return $this;
    }

    /**
     * Add a message action button.
     *
     * @param  string  $label
     * @param  string|null  $text
     * @param  string|null  $imageUrl
     * @return self
     */
    public function message(string $label, string $text = null, string $imageUrl = null)
    {
        return $this->add(
            $this->action->message($label, $text),
            $imageUrl
        );
    }

    /**
     * Add a URL action button.
     *
     * @param  string  $label
     * @param  string  $uri
     * @param  string|null  $alt
     * @param  string|null  $imageUrl
     * @return self
     */
    public function url(string $label, string $uri, string $alt = null, string $imageUrl = null)
    {
        return $this->add(
            $this->action->url($label, $uri, $alt),
            $imageUrl
        );
    }

    /**
     * Add a postback action button.
     *
     * @param  string  $label
     * @param  string  $data
     * @param  string|null  $displayText
     * @param  string|null  $imageUrl
     * @return self
     */
    public function postback(string $label, string $data, string $displayText = null, string $imageUrl = null)
    {
        return $this->add(
            $this->action->postback($label, $data, $displayText),
            $imageUrl
        );
    }

    /**
     * Add a location action button.
     *
     * @param  string  $label
     * @param  string|null  $imageUrl
     * @return self
     */
    public function location(string $label, string $imageUrl = null)
    {
        return $this->add(
            $this->action->location($label),
            $imageUrl
        );
    }

    /**
     * Add a datetime picker action button.
     *
     * @param  string  $label
     * @param  string  $data
     * @param  string  $mode
     * @param  string|null  $initial
     * @param  string|null  $max
     * @param  string|null  $min
     * @param  string|null  $imageUrl
     * @return self
     */
    public function datetimePicker(string $label, string $data, string $mode, string $initial = null, string $max = null, string $min = null, string $imageUrl = null)
    {
        return $this->add(
            $this->action->datetimePicker($label, $data, $mode, $initial, $max, $min),
            $imageUrl
        );
    }

    /**
     * Add a camera action button.
     *
     * @param  string  $label
     * @param  string|null  $imageUrl
     * @return self
     */
    public function camera(string $label, string $imageUrl = null)
    {
        return $this->add(
            $this->action->camera($label),
            $imageUrl
        );
    }

    /**
     * Add a camera roll action button.
     *
     * @param  string  $label
     * @param  string|null  $imageUrl
     * @return self
     */
    public function cameraRoll(string $label, string $imageUrl = null)
    {
        return $this->add(
            $this->action->cameraRoll($label),
            $imageUrl
        );
    }

    /**
     * Build a new quick reply message builder instance.
     *
     * @return \LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder
     */
    public function build()
    {
        return new QuickReplyMessageBuilder($this->actions);
    }
}
