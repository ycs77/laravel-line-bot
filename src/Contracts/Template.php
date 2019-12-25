<?php

namespace Ycs77\LaravelLineBot\Contracts;

use LINE\LINEBot\MessageBuilder\TemplateBuilder;

interface Template
{
    /**
     * Get the template builder instance.
     *
     * @return \LINE\LINEBot\MessageBuilder\TemplateBuilder
     */
    public function getTemplateBuilder(): TemplateBuilder;
}
