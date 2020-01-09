<?php

namespace Ycs77\LaravelLineBot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \LINE\LINEBot\Response|null reply(\LINE\LINEBot\MessageBuilder $messageBuilder)
 * @method static void routes(array $events, \Closure $callback)
 * @method static array eventTransform(array $events)
 * @method static \Illuminate\Http\File file(string $messageId, string $filePath)
 * @method static \Ycs77\LaravelLineBot\Profile profile()
 * @method static \Illuminate\Database\Eloquent\Model|null user()
 * @method static \Ycs77\LaravelLineBot\Message\Builder say()
 * @method static \Ycs77\LaravelLineBot\Action action()
 * @method static \Ycs77\LaravelLineBot\MessageRouter on()
 * @method static \Ycs77\LaravelLineBot\MessageRouter getRouter()
 * @method static \Ycs77\LaravelLineBot\LineBot setRouter(\Ycs77\LaravelLineBot\MessageRouter|\Closure $router)
 * @method static \Ycs77\LaravelLineBot\Matching\Matcher getMatcher()
 * @method static \Ycs77\LaravelLineBot\LineBot setMatcher(\Ycs77\LaravelLineBot\Matching\Matcher $matcher)
 * @method static \Ycs77\LaravelLineBot\File\Factory getFileFactory()
 * @method static \Ycs77\LaravelLineBot\LineBot setFileFactory(\Ycs77\LaravelLineBot\File\Factory $file)
 * @method static \Illuminate\Contracts\Config\Repository|mixed getConfig(array|string|null $key, mixed $default)
 * @method static \Ycs77\LaravelLineBot\Contracts\Event|null getEvent()
 * @method static \Ycs77\LaravelLineBot\LineBot setEvent(\Ycs77\LaravelLineBot\Contracts\Event $event)
 * @method static \LINE\LINEBot base()
 * @method static \Ycs77\LaravelLineBot\Message\Builder text(string $message)
 * @method static \Ycs77\LaravelLineBot\Message\Builder template(string|\LINE\LINEBot\MessageBuilder\TemplateMessageBuilder $altText, \Closure $callback)
 * @method static \Ycs77\LaravelLineBot\Message\Builder quickReply(\Ycs77\LaravelLineBot\QuickReply|\Closure $value)
 *
 * @see \Ycs77\LaravelLineBot\LineBot
 * @see \Ycs77\LaravelLineBot\Message\Builder
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
