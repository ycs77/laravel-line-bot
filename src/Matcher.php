<?php
namespace Ycs77\LaravelLineBot;

use LINE\LINEBot\Event\MessageEvent\TextMessage;

class Matcher
{
    /**
     * regular expression to capture named parameters but not quantifiers
     * captures {name}, but not {1}, {1,}, or {1,2}.
     */
    const PARAM_NAME_REGEX = '/\{((?:(?!\d+,?\d+?)\w)+?)\}/';

    /**
     * The talks matches array.
     *
     * @var array
     */
    protected $matches;

    /**
     * Check the message is match.
     *
     * @param  \Ycs77\LaravelLineBot\Talk  $talk
     * @param  mixed  $event
     * @param  string  $message
     * @return bool
     */
    public function isMatch(Talk $talk, $event)
    {
        if ($event instanceof TextMessage) {
            $this->matches = [];

            $pattern = str_replace('/', '\/', $talk->getPattern());
            $text = '/^' . preg_replace(self::PARAM_NAME_REGEX, '(?<$1>.*)', $pattern) . ' ?$/miu';

            return (bool) preg_match($text, $event->getText(), $this->matches);
        }

        $type = $talk->getType();

        return $event instanceof $type;
    }
}
