<?php

namespace Ycs77\LaravelLineBot\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use Concerns\CommandHelper;

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
            base_path('app/Http/Controllers/LineBotController.php'),
            $this->compileControllerStub()
        );

        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(__DIR__ . '/stubs/' . $this->getLinebotStubRoutesName()),
            FILE_APPEND
        );

        file_put_contents(
            base_path($this->laravel['config']['linebot.routes_path']),
            file_get_contents(__DIR__ . '/stubs/' . $this->getStubRoutesName())
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

    /**
     * Get the routes stub file name.
     *
     * @return string
     */
    protected function getStubRoutesName()
    {
        return $this->isLumen() ? 'linebot-lumen.stub' : 'linebot.stub';
    }

    /**
     * Get the linebot routes stub file name.
     *
     * @return string
     */
    protected function getLinebotStubRoutesName()
    {
        return $this->isLumen() ? 'routes-lumen.stub' : 'routes.stub';
    }
}
