<?php

namespace Ycs77\LaravelLineBot\Message;

use LINE\LINEBot\MessageBuilder\TemplateBuilder as OriginalTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use Ycs77\LaravelLineBot\ActionBuilder;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;
use Ycs77\LaravelLineBot\LineBot;

class TemplateBuilder extends AbstractBuilder
{
    /**
     * The original template instance.
     *
     * @var \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    protected $template;

    /**
     * The action builder instance.
     *
     * @var \Ycs77\LaravelLineBot\ActionBuilder
     */
    protected $actionBuilder;

    /**
     * Create a new builder instance.
     *
     * @param \Ycs77\LaravelLineBot\LineBot $bot
     */
    public function __construct(LineBot $bot)
    {
        parent::__construct($bot);

        $this->actionBuilder = new ActionBuilder($this->bot->action());
    }

    /**
     * Add the button template message.
     *
     * @param  string|null  $title
     * @param  string  $text
     * @param  string|null  $thumbnailImageUrl
     * @param  \LINE\LINEBot\TemplateActionBuilder[]|callable  $actionBuilders
     * @param  string|null  $imageAspectRatio
     * @param  string|null  $imageSize
     * @param  string|null  $imageBackgroundColor
     * @param  \LINE\LINEBot\TemplateActionBuilder|null  $defaultAction
     * @return self
     */
    public function button(
        string $title = null,
        string $text,
        string $thumbnailImageUrl = null,
        $actionBuilders,
        string $imageAspectRatio = null,
        string $imageSize = null,
        string $imageBackgroundColor = null,
        TemplateActionBuilder $defaultAction = null
    ) {
        if (is_callable($actionBuilders)) {
            call_user_func($actionBuilders, $this->actionBuilder);
            $actionBuilders = $this->actionBuilder->get();
        }

        $this->template = new ButtonTemplateBuilder(
            $title,
            $text,
            $thumbnailImageUrl,
            $actionBuilders,
            $imageAspectRatio,
            $imageSize,
            $imageBackgroundColor,
            $defaultAction
        );

        return $this;
    }

    /**
     * Get the template message builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    public function getTemplate()
    {
        if (!$this->template instanceof OriginalTemplateBuilder) {
            $class = OriginalTemplateBuilder::class;

            throw new LineRequestErrorException("The template builder message must implements $class interface");
        }

        return $this->template;
    }
}
