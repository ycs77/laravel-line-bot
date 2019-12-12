<?php

namespace Ycs77\LaravelLineBot;

class Talk
{
    /**
     * The match Line Bot dialog type.
     *
     * @var string
     */
    protected $type;

    /**
     * The match Line Bot dialog request message pattern.
     *
     * @var string
     */
    protected $pattern;

    /**
     * The Line Bot route message callback.
     *
     * @var callable
     */
    protected $reply;

    /**
     * Create a new Line Bot dialog instance.
     *
     * @param  string  $pattern
     * @param  callable  $reply
     * @return void
     */
    public function __construct(string $type, string $pattern, callable $reply)
    {
        $this->type = $type;
        $this->pattern = $pattern;
        $this->reply = $reply;
    }

    /**
     * Get the match dialog type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the match dialog pattern.
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * Return the get message builder callback.
     *
     * @return callable
     */
    public function getReply()
    {
        return $this->reply;
    }
}
