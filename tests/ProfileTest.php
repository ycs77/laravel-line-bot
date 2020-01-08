<?php

namespace Ycs77\LaravelLineBot\Test;

use Ycs77\LaravelLineBot\Profile;

class ProfileTest extends TestCase
{
    public function testNewUserProfile()
    {
        $profile = new Profile([
            'displayName' => 'Lucas',
            'userId' => 'UID12345678',
            'pictureUrl' => 'https://example.com/image/path...',
            'statusMessage' => 'Hello world!',
        ]);

        $this->assertSame('UID12345678', $profile->id());
        $this->assertSame('Lucas', $profile->name());
        $this->assertSame('https://example.com/image/path...', $profile->picture());
        $this->assertSame('Hello world!', $profile->status());
    }
}
