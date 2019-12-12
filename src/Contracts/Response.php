<?php

namespace Ycs77\LaravelLineBot\Contracts;

interface Response
{
    /**
     * Return a success response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success();

    /**
     * Return a fail response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail(string $message);
}
