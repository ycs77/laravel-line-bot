<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Channel Access Token
    |--------------------------------------------------------------------------
    |
    | This value is the Line Bot channel access token.
    |
    */

    'channel_access_token' => env('LINE_BOT_CHANNEL_ACCESS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Channel Secret
    |--------------------------------------------------------------------------
    |
    | This value is the Line Bot channel secret.
    |
    */

    'channel_secret' => env('LINE_BOT_CHANNEL_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Endpoint Base
    |--------------------------------------------------------------------------
    |
    | This value is the Line Bot API endpoint base URL.
    |
    */

    'endpoint_base' => env('LINE_BOT_ENDPOINT_BASE', 'https://api.line.me'),

    /*
    |--------------------------------------------------------------------------
    | Endpoint Base
    |--------------------------------------------------------------------------
    |
    | This value is the Line Bot API endpoint base URL.
    |
    */

    'dataEndpointBase' => env('LINE_BOT_DATA_ENDPOINT_BASE', 'https://api-data.line.me'),

    /*
    |--------------------------------------------------------------------------
    | Cache Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the Line Bot default cache driver name.
    |
    | Reference: "config/cache.php"
    | Supported: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb"
    |
    */

    'cache' => env('LINE_BOT_CACHE_DRIVER') ?? env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Routes Path
    |--------------------------------------------------------------------------
    |
    | This value is the Line Bot routes file path.
    |
    */

    'routes_path' => 'routes/linebot.php',

    /*
    |--------------------------------------------------------------------------
    | User Eloquent Model
    |--------------------------------------------------------------------------
    |
    | This config is setting the Line Bot User Eloquent Model.
    |
    | (wip 功能開發中...)
    |
    */

    'user' => [
        'model' => App\User::class,
        'field' => 'line_user_id',
        'enabled' => false,
    ],

];
