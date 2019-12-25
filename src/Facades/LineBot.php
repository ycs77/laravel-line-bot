<?php

namespace Ycs77\LaravelLineBot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \LINE\LINEBot\Response|null reply(\LINE\LINEBot\MessageBuilder $messageBuilder)
 * @method static \Ycs77\LaravelLineBot\Message\Builder query()
 * @method static \Ycs77\LaravelLineBot\Action action()
 * @method static \Illuminate\Contracts\Config\Repository|mixed getConfig(array|string|null $key, mixed $default)
 * @method static \Ycs77\LaravelLineBot\LineBot setEvent(\LINE\LINEBot\Event\BaseEvent $event)
 * @method static \LINE\LINEBot\Event\BaseEvent|null getEvent()
 * @method static \LINE\LINEBot base()
 * @method static \Ycs77\LaravelLineBot\Message\Builder text(string $message)
 * @method static \Ycs77\LaravelLineBot\Message\Builder template(string|\LINE\LINEBot\MessageBuilder\TemplateMessageBuilder $altText, callable|null $callback = null)
 * @method static \Ycs77\LaravelLineBot\Message\Builder quickReply(callable|\Ycs77\LaravelLineBot\QuickReply $value)
 *
 * @see \Ycs77\LaravelLineBot\LineBot
 */
class LineBot extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'linebot';
    }
}
