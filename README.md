# Laravel Line Bot

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![CI Build Status][ico-ci]][link-ci]
[![Style CI Build Status][ico-style-ci]][link-style-ci]
[![Total Downloads][ico-downloads]][link-downloads]

> 開發中

在 Laravel 中開發 Line Bot

## 安裝前準備

* 建立一個 Laravel 專案
* 準備一個 HTTPS 的網址，建議使用 Ngrok 來建立臨時網址 (開發)
* 增加 Webhook 路徑到 Laravel 的 `VerifyCsrfToken` Middleware 中的 `except` 陣列，以禁用 CSRF 檢查：
    ```php
    protected $except = [
        'webhook',
    ];
    ```

## 安裝

使用 Composer 安裝：

```bash
composer require ycs77/laravel-line-bot
```

發布檔案：

```bash
php artisan vendor:publish --provider=Ycs77\\LaravelLineBot\\LineBotServiceProvider
```

設定 Line Bot 金鑰 (在 [Line Developers](https://developers.line.biz/zh-hant/) 上申請)：

```
LINE_BOT_CHANNEL_ACCESS_TOKEN=xxx...
LINE_BOT_CHANNEL_SECRET=123...
```

## 使用

### Laravel 使用方式

> 功能正在開發中

*app/Http/Controllers/LineController.php*
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\LineBotService;

class LineController extends Controller
{
    use LineBotService;

    public function webhook(Request $request, Response $response)
    {
        return $this->lineBotReply($request, $response);
    }
}

```

*routes/linebot.php*
```php
LineBot::text('hello', function (TextMessage $event) {
    return '哈囉!!';
});
```

### 原本 PHP 使用方式

> 注意：使用本方法時，`routes/linebot.php` 裡的定義將不起作用。

基於上方 `LineController` 來增加方法：

*app/Http/Controllers/LineController.php*
```php
class LineController extends Controller
{
    ...

    protected function reply(array $events)
    {
        $bot = $this->basebot();

        foreach ($events as $event) {
            if ($event instanceof TextMessage) {
                switch ($event->getText()) {
                    case 'hello':
                        $bot->replyText($event->getReplyToken(), '哈囉！');
                        break;

                    default:
                        $this->fallback($bot, $event);
                }

            } else {
                $this->fallback($bot, $event);
            }
        }
    }

    protected function fallback($bot, $event)
    {
        $bot->replyText($event->getReplyToken(), '我不大了解您的意思...');
    }
}

```

## 參考

更多資訊請參考以下資料：

* [Line SDK 文檔](https://developers.line.biz/en/reference/messaging-api/)
* [Line 官方 SDK - PHP](https://github.com/line/line-bot-sdk-php)

[ico-version]: https://img.shields.io/packagist/v/ycs77/laravel-line-bot?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square
[ico-ci]: https://img.shields.io/travis/ycs77/laravel-line-bot?style=flat-square
[ico-style-ci]: https://github.styleci.io/repos/217076147/shield?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ycs77/laravel-line-bot?style=flat-square

[link-packagist]: https://packagist.org/packages/ycs77/laravel-line-bot
[link-ci]: https://travis-ci.org/ycs77/laravel-line-bot
[link-style-ci]: https://github.styleci.io/repos/217076147
[link-downloads]: https://packagist.org/packages/ycs77/laravel-line-bot
