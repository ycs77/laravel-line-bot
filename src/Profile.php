<?php

namespace Ycs77\LaravelLineBot;

class Profile
{
    /**
     * The user profile coontent.
     *
     * @var array
     */
    protected $content;

    /**
     * Create a new Line Bot user profile instance.
     *
     * @param  array  $content
     * @return void
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    /**
     * Get the user profile id.
     *
     * @return string|null
     */
    public function id()
    {
        return $this->content['userId'] ?? null;
    }

    /**
     * Get the user profile display name.
     *
     * @return string|null
     */
    public function name()
    {
        return $this->content['displayName'] ?? null;
    }

    /**
     * Get the user profile picture url.
     *
     * @return string|null
     */
    public function picture()
    {
        return $this->content['pictureUrl'] ?? null;
    }

    /**
     * Get the user profile status message.
     *
     * @return string|null
     */
    public function status()
    {
        return $this->content['statusMessage'] ?? null;
    }
}
