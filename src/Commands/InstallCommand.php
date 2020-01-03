<?php

namespace Ycs77\LaravelLineBot\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'linebot:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic LineBot controllers and routes';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->exportFiles();

        $this->info('LineBot scaffolding generated successfully.');
    }

    /**
     * Export the LineBot files.
     *
     * @return void
     */
    protected function exportFiles()
    {
        file_put_contents(
            app_path('Http/Controllers/LineBotController.php'),
            $this->compileControllerStub()
        );

        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(__DIR__ . '/stubs/routes.stub'),
            FILE_APPEND
        );

        file_put_contents(
            base_path($this->laravel['config']['linebot.routes_path']),
            file_get_contents(__DIR__ . '/stubs/linebot.stub')
        );
    }

    /**
     * Compiles the "HomeController" stub.
     *
     * @return string
     */
    protected function compileControllerStub()
    {
        return str_replace(
            '{{namespace}}',
            $this->laravel->getNamespace(),
            file_get_contents(__DIR__ . '/stubs/controllers/LineBotController.stub')
        );
    }
}
