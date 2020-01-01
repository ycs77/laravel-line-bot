<?php

namespace Ycs77\LaravelLineBot\Commands\Concerns;

use Illuminate\Config\Repository as Config;
use Illuminate\Support\Collection;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\Response;

trait LineBotRichMenuCommand
{
    /**
     * The Line Bot instance.
     *
     * @var \LINE\LINEBot
     */
    protected $bot;

    /**
     * The Line Bot instance.
     *
     * @var \LINE\LINEBot\HTTPClient
     */
    protected $http;

    /**
     * The Line Bot config.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $config;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(LINEBot $bot, HTTPClient $http, Config $config)
    {
        parent::__construct();

        $this->bot = $bot;
        $this->http = $http;
        $this->config = Collection::make($config->get('linebot'));
    }

    /**
     * Post to create rich menu fail.
     *
     * @param  \LINE\LINEBot\Response  $response
     * @return \LINE\LINEBot\Response
     */
    protected function createRichMenuFail(Response $response)
    {
        $this->error('Create the Line Bot rich menu is fail.');
        $this->error($response->getRawBody());
    }

    /**
     * Post to create rich menu fail.
     *
     * @param  \LINE\LINEBot\Response  $response
     * @return \LINE\LINEBot\Response
     */
    protected function getRichMenuListFail(Response $response)
    {
        $this->error('Get the Line Bot rich menu list is fail.');
        $this->error($response->getRawBody());
    }

    /**
     * Get the rich menus array from response body.
     *
     * @param  \LINE\LINEBot\Response  $response
     * @return \Illuminate\Support\Collection
     */
    protected function getRichMenus(Response $response)
    {
        return Collection::make($response->getJSONDecodedBody()['richmenus']);
    }

    /**
     * Get the rich menu ids array from response body.
     *
     * @param  \LINE\LINEBot\Response  $response
     * @return array
     */
    protected function getRichMenuIds(Response $response)
    {
        return $this->getRichMenus($response)
            ->map(function ($richMenu) {
                return $richMenu['richMenuId'];
            })
            ->toArray();
    }

    /**
     * Check the http response is fail.
     *
     * @param  \LINE\LINEBot\Response  $response
     * @return bool
     */
    protected function isFail(Response $response)
    {
        return !$response->isSucceeded();
    }
}
