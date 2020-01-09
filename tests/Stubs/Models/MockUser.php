<?php

namespace Ycs77\LaravelLineBot\Test\Stubs\Models;

use Ycs77\LaravelLineBot\Contracts\User as UserContract;
use Ycs77\LaravelLineBot\Profile;

class MockUser implements UserContract
{
    protected $attributes = [];

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function storeFromLineBotProfile(Profile $profile)
    {
        return new static([
            'name' => $profile->name(),
            'line_user_id' => $profile->id(),
        ]);
    }

    public function is($model)
    {
        $class = static::class;

        return $model instanceof $class;
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }
}
