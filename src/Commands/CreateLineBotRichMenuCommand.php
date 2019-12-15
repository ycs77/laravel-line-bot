<?php

namespace Ycs77\LaravelLineBot\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use LINE\LINEBot\Response;

class CreateLineBotRichMenuCommand extends Command
{
    use LineBotRichMenuCommand;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'linebot:richmenu:create
                            {image : The rich menu image. ex: "public/images/image.jpg"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new rich menu for Line Bot and upload image';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = $this->createRichMenu();

        if ($this->isFail($response)) {
            return $this->createRichMenuFail($response);
        }

        $response = $this->bot->getRichMenuList();

        if ($this->isFail($response)) {
            return $this->getRichMenuListFail($response);
        }

        $richMenu = $this->getRichMenu($response);
        $response = $this->uploadRichMenuImage($richMenu);

        if ($this->isFail($response)) {
            return $this->uploadRichMenuImageFail($response);
        }

        $response = $this->bot->linkRichMenu('all', $richMenu['richMenuId']);

        if ($this->isFail($response)) {
            return $this->uploadRichMenuImageFail($response);
        }

        $this->info('Create the Line Bot rich menu is successfully.');
    }

    /**
     * Post to create rich menu.
     *
     * @return \LINE\LINEBot\Response
     */
    protected function createRichMenu()
    {
        $richmenuConfig = $this->config->get('rich_menu');
        $richmenuUrl = $this->config->get('endpoint_base') . '/v2/bot/richmenu';

        return $this->http->post($richmenuUrl, $richmenuConfig);
    }

    /**
     * Upload the rich menu image.
     *
     * @param  array  $richMenu
     * @return \LINE\LINEBot\Response
     */
    protected function uploadRichMenuImage(array $richMenu)
    {
        $imagePath = $this->argument('image');
        $contentType = $this->getImageContentType($imagePath);

        return $this->bot->uploadRichMenuImage(
            $richMenu['richMenuId'],
            $imagePath,
            $contentType
        );
    }

    /**
     * Upload the rich menu image fail.
     *
     * @param  \LINE\LINEBot\Response  $response
     * @return \LINE\LINEBot\Response
     */
    protected function uploadRichMenuImageFail(Response $response)
    {
        $this->error('Upload the Line Bot rich menu image is fail.');
        $this->error($response->getRawBody());

        return;
    }

    /**
     * Get the rich menu image's content type.
     *
     * Support: image/jpeg, image/png
     *
     * @param  string  $imagePath
     * @return string
     */
    protected function getImageContentType(string $imagePath)
    {
        return Str::endsWith($imagePath, '.png') ? 'image/png' : 'image/jpeg';
    }
}
