<?php

namespace Ycs77\LaravelLineBot\File;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Factory
{
    /**
     * Create a new uploaded file instance.
     *
     * @param  string  $content
     * @param  string  $dir
     * @param  array|string  $options
     * @return \Illuminate\Http\File
     */
    public function create($content, string $dir, $options = [])
    {
        $tmpfile = tmpfile();
        fwrite($tmpfile, $content);
        $path = stream_get_meta_data($tmpfile)['uri'];

        $uploadedFile = new UploadedFile($path, basename($path));

        $filepath = Storage::disk()->path(
            $uploadedFile->store($dir, $options)
        );

        return new File($filepath);
    }
}
