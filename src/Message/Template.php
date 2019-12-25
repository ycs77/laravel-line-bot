<?php

namespace Ycs77\LaravelLineBot\Message;

use Closure;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use Ycs77\LaravelLineBot\Contracts\Message;
use Ycs77\LaravelLineBot\Contracts\QuickReplyMessage;
use Ycs77\LaravelLineBot\LineBot;

class Template implements Message, QuickReplyMessage
{
    use Concerns\QuickReplyable;

    /**
     * The Line Bot instance.
     *
     * @var \Ycs77\LaravelLineBot\LineBot
     */
    protected $bot;

    /**
     * The action builder instance.
     *
     * @var \Ycs77\LaravelLineBot\ActionBuilder
     */
    protected $actionBuilder;

    /**
     * The original template instance.
     *
     * @var \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    protected $instance;

    /**
     * The template alt text.
     *
     * @var string
     */
    protected $altText;

    /**
     * The template builder closure.
     *
     * @var \Closure|null
     */
    protected $callback;

    /**
     * The original template message builder instance.
     *
     * @var \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    protected $messageBuilder;

    /**
     * Create a new template instance.
     *
     * @param  string|\LINE\LINEBot\MessageBuilder\TemplateMessageBuilder  $altText
     * @param  \Closure|null  $callback
     * @return void
     */
    public function __construct(LineBot $bot, $altText, Closure $callback = null)
    {
        $this->bot = $bot;
        $this->callback = $callback;

        if ($altText instanceof TemplateMessageBuilder) {
            $this->messageBuilder = $altText;
        } else {
            $this->altText = $altText;
        }
    }

    /**
     * Get the action factory instance.
     *
     * @return \Ycs77\LaravelLineBot\ActionBuilder
     */
    public function action()
    {
        if (!$this->actionBuilder) {
            $this->actionBuilder = new ActionBuilder($this->bot->action());
        }

        return $this->actionBuilder;
    }

    /**
     * Get the message builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function getMessageBuilder()
    {
        if ($this->messageBuilder instanceof TemplateMessageBuilder) {
            return $this->messageBuilder;
        }

        $template = $this->getTemplate();

        return new TemplateMessageBuilder(
            $this->altText,
            $template,
            $this->buildQuickReply()
        );
    }

    /**
     * Get the template builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    public function getTemplate()
    {
        $template = new TemplateBuilder($this->bot);

        call_user_func($this->callback, $template);

        return $template->getTemplate();
    }
}
