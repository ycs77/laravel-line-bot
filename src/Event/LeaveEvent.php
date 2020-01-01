<?php

namespace Ycs77\LaravelLineBot\Event;

use Ycs77\LaravelLineBot\Contracts\Event;
use Ycs77\LaravelLineBot\Event\Concerns\Eventable;

class LeaveEvent implements Event
{
    use Eventable;
}
