<?php

namespace Ycs77\LaravelLineBot\Test\Matching;

use Ycs77\LaravelLineBot\Matching\TextMessageParameters;
use Ycs77\LaravelLineBot\Test\TestCase;

class TextMessageParametersTest extends TestCase
{
    public function testGetParameters()
    {
        $parameters = ['小明', '10'];

        $matches = [
            0 => '我的名字是小明，今年10歲',
            'name' => '小明',
            1 => '小明',
            'age' => '10',
            2 => '10',
        ];

        $names = ['name', 'age'];

        $this->assertSame($parameters, TextMessageParameters::get($matches, $names));
    }

    public function testCompileParameterNames()
    {
        $pattern = '我的名字是{name}，今年{age}歲';

        $names = ['name', 'age'];

        $this->assertSame($names, TextMessageParameters::compileNames($pattern));
    }
}
