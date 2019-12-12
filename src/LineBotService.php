<?php

namespace Ycs77\LaravelLineBot;

use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;
use Ycs77\LaravelLineBot\Contracts\Response;

trait LineBotService
{
    /**
     * Run the Line Bot reply.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Ycs77\LaravelLineBot\Contracts\Response  $response
     * @return \Ycs77\LaravelLineBot\LineBot
     */
    protected function lineBotReply(Request $request, Response $response)
    {
        try {
            $signature = $this->getSignature($request);
            $body = $this->getRequestBody($request);
            $events = $this->bot()->parseRequest($body, $signature);
        } catch (\Exception $e) {
            return $response->fail($e->getMessage());
        }

        $this->reply($events);

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
     * Used Line sdk reply the messages.
     *
     * @return void
     */
    protected function reply(array $events)
    {
        $this->bot()->reply($events);
    }

    /**
     * Get the Line Bot instance.
     *
     * @return \Ycs77\LaravelLineBot\LineBot
     */
    protected function bot()
    {
        return app(LineBot::class);
    }

    /**
     * Get the Line official sdk Bot instance.
     *
     * @return \LINE\LINEBot
     */
    protected function basebot()
    {
        return $this->bot()->base();
    }
}
