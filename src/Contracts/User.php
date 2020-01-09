<?php

namespace Ycs77\LaravelLineBot\Contracts;

use Ycs77\LaravelLineBot\Profile;

interface User
{
    /**
     * Store user from LineBot profile.
     *
     * @param  \Ycs77\LaravelLineBot\Profile  $profile
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeFromLineBotProfile(Profile $profile);
}
