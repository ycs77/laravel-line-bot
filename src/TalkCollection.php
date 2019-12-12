<?php

namespace Ycs77\LaravelLineBot;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class TalkCollection
{
    /**
     * A listen conversations.
     *
     * @var array
     */
    protected $listenItems = [];

    /**
     * Add a new talk instance to listen items.
     *
     * @param  \Ycs77\LaravelLineBot\Talk  $talk
     * @return \Ycs77\LaravelLineBot\Talk
     */
    public function add(Talk $talk)
    {
        $this->listenItems[] = $talk;

        return $talk;
    }

    /**
     * Match the Line Bot routes.
     *
     * @param  mixed  $event
     * @return array
     */
    public function matchMessages($event)
    {
        $machter = new Matcher();
        $matchMessages = [];

        /** @var \Ycs77\LaravelLineBot\Talk $talk */
        foreach ($this->listenItems as $talk) {
            if ($machter->isMatch($talk, $event)) {
                try {
                    $matchMessages[] = $this->getMessageBuilder(
                        $event,
                        $talk->getReply()
                    );
                } catch (\Throwable $th) {
                    dump($th->getMessage());
                }
            }
        }

        if (!count($matchMessages)) {
            // fallback
        }

        return $matchMessages;
    }

    /**
     * Get message builder instance.
     *
     * @param  mixed  $event
     * @param  callable  $getMessage
     * @param  string  $messageBuilderClass
     * @return \LINE\LINEBot\MessageBuilder
     */
    public function getMessageBuilder($event, callable $getMessage)
    {
        $message = call_user_func($getMessage, $event);

        if ($message instanceof MessageBuilder) {
            return $message;
        }

        return new TextMessageBuilder($message);
    }

    /**
     * Get the all routes.
     *
     * @return array
     */
    public function getTalks()
    {
        return $this->items;
    }

    /**
     * Set the all routes.
     *
     * @param  array  $items
     * @return self
     */
    public function setTalks(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
