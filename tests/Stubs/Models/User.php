<?php

namespace Ycs77\LaravelLineBot\Test\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use Ycs77\LaravelLineBot\CanStoreLineBotUser;
use Ycs77\LaravelLineBot\Contracts\User as UserContract;

class User extends Model implements UserContract
{
    use CanStoreLineBotUser;

    protected $table = 'users';

    protected $fillable = [
        'name', 'line_user_id',
    ];
}
