<?php

namespace Ycs77\LaravelLineBot\Test\File;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Ycs77\LaravelLineBot\File\Factory as FileFactory;
use Ycs77\LaravelLineBot\Test\TestCase;

class FileFactoryTest extends TestCase
{
    public function testCreateNewUploadedFile()
    {
        Storage::fake();

        $factory = new FileFactory();

        $file = $factory->create('content...', 'linebot');

        $this->assertInstanceOf(File::class, $file);
        Storage::assertExists('linebot/' . $file->getFilename());
    }
}
