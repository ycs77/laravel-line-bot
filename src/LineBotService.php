<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;
use Ycs77\LaravelLineBot\Contracts\Response;
use Ycs77\LaravelLineBot\Exceptions\LineRequestErrorException;

trait LineBotService
{
    /**
     * Run the Line Bot reply.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Ycs77\LaravelLineBot\Contracts\Response  $response
     * @return \Illuminate\Http\JsonResponse
     */
    protected function lineBotReply(Request $request, Response $response)
    {
        try {
            $signature = $this->getSignature($request);
            $body = $this->getRequestBody($request);
            $events = $this->bot()->base()->parseEventRequest($body, $signature);

            $this->reply($events);
        } catch (LineRequestErrorException $e) {
            return $response->fail($e->getMessage());
        }

        return $response->success();
    }

    /**
     * Get the Line Bot request signature.
     *
     * @return string
     */
    protected function getSignature(Request $request)
    {
        return $request->header(HTTPHeader::LINE_SIGNATURE);
    }

    /**
     * Get the request content body.
     *
     * @return string
     */
    protected function getRequestBody(Request $request)
    {
        return $request->getContent();
    }

    /**
     * Reply the messages.
     *
     * @param  array  $events
     * @return void
     */
    protected function reply(array $events)
    {
        $this->bot()->routes($events, function () {
            include base_path(config('linebot.routes_path'));
        });
    }

    /**
     * Get the Line Bot instance.
     *
     * @return \Ycs77\LaravelLineBot\LineBot
     */
    public function bot()
    {
        return app(LineBot::class);
    }
}
