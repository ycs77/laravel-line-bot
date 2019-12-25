<?php

namespace Ycs77\LaravelLineBot\Message;

use Ycs77\LaravelLineBot\Contracts\Message;
use Ycs77\LaravelLineBot\Contracts\QuickReplyMessage;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;
use Ycs77\LaravelLineBot\LineBot;
use Ycs77\LaravelLineBot\QuickReplyBuilder;

abstract class AbstractBuilder
{
    /**
     * The Line Bot instance.
     *
     * @var \Ycs77\LaravelLineBot\LineBot
     */
    protected $bot;

    /**
     * The message instance.
     *
     * @var \Ycs77\LaravelLineBot\Message\Message
     */
    protected $message;

    /**
     * The quick reply instance.
     *
     * @var \Ycs77\LaravelLineBot\QuickReplyBuilder
     */
    protected $quickReply;

    /**
     * Create a new builder instance.
     *
     * @param \Ycs77\LaravelLineBot\LineBot $bot
     */
    public function __construct(LineBot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * Add the quick reply messages.
     *
     * @param  \Ycs77\LaravelLineBot\QuickReplyBuilder|callable  $value
     * @return self
     */
    public function quickReply($value)
    {
        if ($value instanceof QuickReplyBuilder) {
            $this->quickReply = $value;
        } elseif (is_callable($value)) {
            $this->quickReply = new QuickReplyBuilder($this->bot->action());
            call_user_func($value, $this->quickReply);
        }

        return $this;
    }

    /**
     * Get the message builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder
     *
     * @throws \Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException
     */
    public function getMessageBuilder()
    {
        if (!$this->message instanceof Message) {
            $class = Message::class;

            throw new LineRequestErrorException("The builder message must implements $class interface");
        }

        if ($this->message instanceof QuickReplyMessage && $this->quickReply) {
            $this->message->setQuickReply($this->quickReply);
        }

        return $this->message->getMessageBuilder();
    }

    /**
     * Reply the message and return Line Bot instance to close this builder.
     *
     * @return \Ycs77\LaravelLineBot\LineBot
     *
     * @throws \Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException
     */
    public function reply()
    {
        $response = $this->bot->reply(
            $this->getMessageBuilder()
        );

        if (!$response->isSucceeded()) {
            $message = $this->bot->getConfig('app.debug')
                ? $response->getRawBody()
                : 'The request error!';

            throw new LineRequestErrorException($message);
        }

        return $this->bot;
    }
}
