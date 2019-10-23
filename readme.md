# Laravel Line Bot

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Style CI Build Status][ico-style-ci]][link-style-ci]
[![Total Downloads][ico-downloads]][link-downloads]

LINE Messaging API SDK for Laravel.

## Installation

Install use Composer:

```bash
composer require ycs77/laravel-line-bot
```

Publish config:

```bash
php artisan vendor:publish --tag=linebot
```

## Getting started

```php
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Ycs77\LaravelLineBot\Facades\LineBot;

$textMessageBuilder = new TextMessageBuilder('hello');
$response = LineBot::replyMessage('<reply token>', $textMessageBuilder);
...
```

## More information

For more information, see the [official API documents](https://github.com/line/line-bot-sdk-php#documentation) and PHPDoc.

[ico-version]: https://img.shields.io/packagist/v/ycs77/laravel-line-bot?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square
[ico-style-ci]: https://github.styleci.io/repos/217076147/shield?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ycs77/laravel-line-bot?style=flat-square

[link-packagist]: https://packagist.org/packages/ycs77/laravel-line-bot
[link-style-ci]: https://github.styleci.io/repos/217076147
[link-downloads]: https://packagist.org/packages/ycs77/laravel-line-bot
