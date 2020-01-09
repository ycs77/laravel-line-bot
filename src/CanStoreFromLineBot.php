<?php

namespace Ycs77\LaravelLineBot;

use Ycs77\LaravelLineBot\Profile;

/**
 * @mixed \Illuminate\Database\Eloquent\Model
 */
trait CanStoreFromLineBot
{
    /**
     * Store user from LineBot profile.
     *
     * @param  \Ycs77\LaravelLineBot\Profile  $profile
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function storeFromLineBotProfile(Profile $profile)
    {
        return $this->query()->updateOrCreate([
            config('linebot.user.id', 'line_user_id') => $profile->id(),
        ], [
            'name' => $profile->name(),
        ]);
    }
}
