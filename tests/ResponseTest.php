<?php

namespace Ycs77\LaravelLineBot\Test;

use Illuminate\Contracts\Routing\ResponseFactory;
use Ycs77\LaravelLineBot\Response;

class ResponseTest extends TestCase
{
    public function testReturnSuccessResponse()
    {
        $response = new Response(
            $this->app->make(ResponseFactory::class)
        );

        $expected = [
            'state' => true,
        ];

        $actual = $response->success()->getData(true);

        $this->assertEquals($expected, $actual);
    }

    public function testReturnFailResponse()
    {
        $response = new Response(
            $this->app->make(ResponseFactory::class)
        );

        $expected = [
            'state' => false,
            'message' => 'error messages',
        ];

        $actual = $response->fail('error messages')->getData(true);

        $this->assertEquals($expected, $actual);
    }
}
