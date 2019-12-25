<?php

namespace Ycs77\LaravelLineBot\Message\Concerns;

use Ycs77\LaravelLineBot\QuickReplyBuilder;

trait QuickReplyable
{
    /**
     * The quick reply instance.
     *
     * @var \Ycs77\LaravelLineBot\QuickReplyBuilder
     */
    protected $quickReply;

    /**
     * Set the quick reply instance.
     *
     * @param  \Ycs77\LaravelLineBot\QuickReplyBuilder  $quickReply
     * @return self
     */
    public function setQuickReply(QuickReplyBuilder $quickReply)
    {
        $this->quickReply = $quickReply;

        return $this;
    }

    /**
     * Build the quick reply message builder instance.
     *
     * @return \LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder|null
     */
    public function buildQuickReply()
    {
        if (!$this->quickReply) {
            return;
        }

        return $this->quickReply->build();
    }
}
