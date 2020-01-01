<?php

namespace Ycs77\LaravelLineBot\Message;

use Closure;

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
     * @param  \Closure  $callback
     * @return self
     */
    public function template($altText, Closure $callback)
    {
        $this->message = new Template($this->bot, $altText, $callback);

        return $this;
    }
}
