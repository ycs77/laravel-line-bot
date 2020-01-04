<?php

namespace Ycs77\LaravelLineBot\Test;

use Ycs77\LaravelLineBot\User;

class UserTest extends TestCase
{
    public function testNewUser()
    {
        $user = new User([
            'displayName' => 'Lucas',
            'userId' => 'UID12345678',
            'pictureUrl' => 'https://example.com/image/path...',
            'statusMessage' => 'Hello world!',
        ]);

        $this->assertSame('UID12345678', $user->id());
        $this->assertSame('Lucas', $user->name());
        $this->assertSame('https://example.com/image/path...', $user->picture());
        $this->assertSame('Hello world!', $user->status());
    }
}
