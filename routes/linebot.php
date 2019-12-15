<?php

use Ycs77\LaravelLineBot\Facades\LineBot;

LineBot::text('hi', function ($event) {
    return 'Hello world!';
});
