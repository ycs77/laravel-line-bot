<?php

namespace Ycs77\LaravelLineBot;

use LINE\LINEBot\TemplateActionBuilder;

class ActionBuilder
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
    public function add(TemplateActionBuilder $actionBuilder)
    {
        $this->actions[] = $actionBuilder;

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
    public function message(string $label, string $text = null)
    {
        return $this->add($this->action->message($label, $text));
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
    public function url(string $label, string $uri, string $alt = null)
    {
        return $this->add($this->action->url($label, $uri, $alt));
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
    public function postback(string $label, string $data, string $displayText = null)
    {
        return $this->add($this->action->postback($label, $data, $displayText));
    }

    /**
     * Add a location action button.
     *
     * @param  string  $label
     * @param  string|null  $imageUrl
     * @return self
     */
    public function location(string $label)
    {
        return $this->add($this->action->location($label));
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
    public function datetimePicker(string $label, string $data, string $mode, string $initial = null, string $max = null, string $min = null)
    {
        return $this->add($this->action->datetimePicker($label, $data, $mode, $initial, $max, $min));
    }

    /**
     * Add a camera action button.
     *
     * @param  string  $label
     * @param  string|null  $imageUrl
     * @return self
     */
    public function camera(string $label)
    {
        return $this->add($this->action->camera($label));
    }

    /**
     * Add a camera roll action button.
     *
     * @param  string  $label
     * @param  string|null  $imageUrl
     * @return self
     */
    public function cameraRoll(string $label)
    {
        return $this->add($this->action->cameraRoll($label));
    }
}
