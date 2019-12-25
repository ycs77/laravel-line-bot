<?php

namespace Ycs77\LaravelLineBot\Message;

use Ycs77\LaravelLineBot\Message\Template;

class Builder extends AbstractBuilder
{
    /**
     * Add the text message.
     *
     * @param  string  $message
     * @return self
     */
    public function text(string $message)
    {
        $this->message = new Text($message);

        return $this;
    }

    /**
     * Add the template message.
     *
     * @param  string|\LINE\LINEBot\MessageBuilder\TemplateMessageBuilder  $altText
     * @param  callable|null  $altText
     * @return self
     */
    public function template($altText, callable $callback = null)
    {
        $this->message = new Template($this->bot, $altText, $callback);

        return $this;
    }
}
