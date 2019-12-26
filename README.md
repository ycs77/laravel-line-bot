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

發布檔案 (選用)：

```bash
php artisan vendor:publish --provider=Ycs77\\LaravelLineBot\\LineBotServiceProvider
```

設定 Line Bot 金鑰到 `.env` 檔裡 (在 [Line Developers](https://developers.line.biz/zh-hant/) 上申請)：

```
LINE_BOT_CHANNEL_ACCESS_TOKEN=xxx...
LINE_BOT_CHANNEL_SECRET=123...
```

## 使用

複製以下範例為 `Controller` 即可開始開發 Line Bot：

> 需要注意的是，這裡的 `Response` 是引用 `\Ycs77\LaravelLineBot\Contracts\Response`。

*app/Http/Controllers/LineController.php*
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot\Event\BaseEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\Facades\LineBot;
use Ycs77\LaravelLineBot\LineBotService;

class LineController extends Controller
{
    use LineBotService;

    public function webhook(Request $request, Response $response)
    {
        return $this->lineBotReply($request, $response);
    }

    protected function reply(array $events)
    {
        /** @var \LINE\LINEBot\Event\BaseEvent $event */
        foreach ($events as $event) {
            LineBot::setEvent($event);

            if ($event instanceof TextMessage) {
                switch ($event->getText()) {
                    case '嗨':
                        LineBot::text('你好')->reply();
                        break;

                    default:
                        $this->fallback($event);
                }
            }
        }
    }

    protected function fallback(BaseEvent $event)
    {
        LineBot::text('我不大了解您的意思...')->reply();
    }
}

```

更多資訊請參考 Line 官方文檔：

* [Line SDK 文檔](https://developers.line.biz/en/reference/messaging-api/)
* [Line 官方 SDK - PHP](https://github.com/line/line-bot-sdk-php)

## Artisan 命令

### Rich Menu

#### 新增 Rich Menu 和上傳圖片

先在 `config/linebot.php` 中設定好 `rich_menu`，和準備一張圖片，執行 `linebot:richmenu:create` 命令以新增 Rich Menu：

> 範例中圖片路徑為 `"public/image.jpg"`，可以修改成實際圖片的路徑。

```
php artisan linebot:richmenu:create "public/image.jpg"
```

#### 查看 Rich Menu

查看全部 Rich Menu ID：

```
php artisan linebot:richmenu:list
```

查看全部 Rich Menu 的原始資料：

```
php artisan linebot:richmenu:list --raw
```

#### 刪除 Rich Menu

刪除指定 Rich Menu ID：

```
php artisan linebot:richmenu:clear richmenuid-sdg24sd56gf...
```

刪除全部 Rich Menu：

```
php artisan linebot:richmenu:clear --all
```

## 測試

運行測試：

```
composer test
```

運行指令產生測試覆蓋率報告，報告產生在 `build/coverage-report`：

```
composer coverage
```

[ico-version]: https://img.shields.io/packagist/v/ycs77/laravel-line-bot?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square
[ico-ci]: https://img.shields.io/travis/ycs77/laravel-line-bot?style=flat-square
[ico-style-ci]: https://github.styleci.io/repos/217076147/shield?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ycs77/laravel-line-bot?style=flat-square

[link-packagist]: https://packagist.org/packages/ycs77/laravel-line-bot
[link-ci]: https://travis-ci.org/ycs77/laravel-line-bot
[link-style-ci]: https://github.styleci.io/repos/217076147
[link-downloads]: https://packagist.org/packages/ycs77/laravel-line-bot
