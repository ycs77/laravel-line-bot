<?php

namespace Ycs77\LaravelLineBot\Commands;

use Illuminate\Console\Command;

class GetLineBotRichMenuListCommand extends Command
{
    use Concerns\LineBotRichMenuCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'linebot:richmenu:list
                            {--raw : Show raw rich menu data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the rich menu list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = $this->bot->getRichMenuList();

        if ($this->isFail($response)) {
            return $this->getRichMenuListFail($response);
        }

        if ($this->option('raw')) {
            $this->info($response->getRawBody());
        } else {
            $richMenuIds = $this->getRichMenuIds($response);

            if (count($richMenuIds)) {
                foreach ($this->getRichMenuIds($response) as $richMenuId) {
                    $this->info($richMenuId);
                }
            } else {
                $this->info('The rich menu is empty.');
            }
        }
    }
}
