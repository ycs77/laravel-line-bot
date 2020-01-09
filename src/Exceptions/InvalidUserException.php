<?php

namespace Ycs77\LaravelLineBot\Exceptions;

use Ycs77\LaravelLineBot\Contracts\User as UserContract;

class InvalidUserException extends LineRequestErrorException
{
    /**
     * Create a new exception.
     *
     * @return void
     */
    public function __construct()
    {
        $class = UserContract::class;

        parent::__construct("The user model must implement $class interface");
    }
}
