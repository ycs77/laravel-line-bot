<?php

namespace Ycs77\LaravelLineBot\Contracts;

use Ycs77\LaravelLineBot\QuickReplyBuilder;

interface QuickReplyMessage
{
    /**
     * Set the quick reply instance.
     *
     * @param  \Ycs77\LaravelLineBot\QuickReplyBuilder  $quickReply
     * @return self
     */
    public function setQuickReply(QuickReplyBuilder $quickReply);

    /**
     * Build the quick reply message builder instance.
     *
     * @return \LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder|null
     */
    public function buildQuickReply();
}
