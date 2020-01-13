<?php

namespace Ycs77\LaravelLineBot;

use Ycs77\LaravelLineBot\Contracts\Response as ResponseContract;

class Response implements ResponseContract
{
    /**
     * The response factory instance.
     *
     * @var \Illuminate\Contracts\Routing\ResponseFactory|\Laravel\Lumen\Http\ResponseFactory
     */
    protected $factory;

    /**
     * Create a new response instance.
     *
     * @param \Illuminate\Contracts\Routing\ResponseFactory|\Laravel\Lumen\Http\ResponseFactory $factory
     */
    public function __construct($factory)
    {
        $this->factory = $factory;
    }

    /**
     * Return a success response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function success()
    {
        return $this->factory->json([
            'state' => true,
        ]);
    }

    /**
     * Return a fail response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function fail(string $message)
    {
        return $this->factory->json([
            'state' => false,
            'message' => $message,
        ]);
    }
}
