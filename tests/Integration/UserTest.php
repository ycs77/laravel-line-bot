<?php

namespace Ycs77\LaravelLineBot\Test\Integration;

use Ycs77\LaravelLineBot\Contracts\User as UserContract;
use Ycs77\LaravelLineBot\Profile;
use Ycs77\LaravelLineBot\Test\Stubs\Models\User;
use Ycs77\LaravelLineBot\Test\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    public function testCreateUserModel()
    {
        $profile = new Profile([
            'displayName' => 'Lucas',
            'userId' => 'UID12345678',
            'pictureUrl' => 'https://example.com/image/path...',
            'statusMessage' => 'Hello world!',
        ]);

        $user = (new User)->storeFromLineBotProfile($profile);

        $this->assertDatabaseHas('users', [
            'name' => 'Lucas',
            'line_user_id' => 'UID12345678',
        ]);

        $this->assertInstanceOf(UserContract::class, $user);
        $this->assertSame($user->name, 'Lucas');
        $this->assertSame($user->line_user_id, 'UID12345678');
    }
}
