<?php

use Ycs77\LaravelLineBot\Facades\LineBot;

LineBot::text(function ($event) {
    return $event;
});
