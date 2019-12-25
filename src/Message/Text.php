<?php

namespace Ycs77\LaravelLineBot\Message;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Ycs77\LaravelLineBot\Contracts\Message;
use Ycs77\LaravelLineBot\Contracts\QuickReplyMessage;

class Text implements Message, QuickReplyMessage
{
    use Concerns\QuickReplyable;

    /**
     * The text value.
     *
     * @var string
     */
    protected $text;

    /**
     * Create a new text message instance.
     *
     * @return void
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Get the message builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function getMessageBuilder()
    {
        return new TextMessageBuilder($this->text, $this->buildQuickReply());
    }
}
