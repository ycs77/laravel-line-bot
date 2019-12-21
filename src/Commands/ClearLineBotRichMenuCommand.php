<?php

namespace Ycs77\LaravelLineBot\Commands;

use Illuminate\Console\Command;

class ClearLineBotRichMenuCommand extends Command
{
    use Concerns\LineBotRichMenuCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'linebot:richmenu:clear
                            {id?* : The rich menu id to delete}
                            {--all : Clear the all rich menu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear a rich menu for Line Bot';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('all')) {
            $response = $this->bot->getRichMenuList();

            if ($this->isFail($response)) {
                return $this->getRichMenuListFail($response);
            }

            $richMenuIds = $this->getRichMenuIds($response);
        } elseif (count($this->argument('id'))) {
            $richMenuIds = $this->argument('id');
        } else {
            return $this->error('The id is fail.');
        }

        foreach ($richMenuIds as $richMenuId) {
            $this->bot->deleteRichMenu($richMenuId);
        }

        $this->info('Clear the Line Bot rich menu is successfully.');
    }
}
