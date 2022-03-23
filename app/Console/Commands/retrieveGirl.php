<?php

namespace App\Console\Commands;

use HeadlessChromium\Page;
use Illuminate\Console\Command;
use HeadlessChromium\BrowserFactory;
use http\Env;
use Illuminate\Support\Str;

define('path', env('SAVE_TO_FILE_PATH'));
class retrieveGirl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retrievegirl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $browserFactory = new BrowserFactory();
        $browser = $browserFactory->createBrowser();
        try {
            $page = $browser->createPage();
            $page->navigate('https://www.seoghoer.dk/tags/se-og-hoer-pigen')->waitForNavigation(Page::DOM_CONTENT_LOADED, 40000);
            $this->line('Connected');
            $elem = $page->dom()->querySelector('.sh-teaser__blocklink')->getAttribute('href');
            $page->navigate('https://www.seoghoer.dk' . $elem)->waitForNavigation(Page::DOM_CONTENT_LOADED, 40000);
            $link = 'https://www.seoghoer.dk' . $elem;
            $img = $page->dom()->querySelector('.img__thumbnail')->getAttribute('src');
            $title = $page->dom()->querySelector('.article-title')->getText();
            $teaser = $page->dom()->querySelector('.article-teaser')->getText();
            $text = $page->dom()->querySelector('.content-body')->querySelector('p')->getHTML();
            $this->line("Found text");
            $page->navigate($img)->waitForNavigation(Page::DOM_CONTENT_LOADED, 40000);
            sleep(2);
            $this->line("Taking screenshot");
//            $screenshot = $page->screenshot([
//                'format'  => 'jpeg',  // default to 'png' - possible values: 'png', 'jpeg',
//                'quality' => 80,      // only if format is 'jpeg' - default 100
//            ]);
//            $fileName = Str::uuid();
//            $screenshot->saveToFile(path . $fileName . ".jpg");

        } finally {
            // bye
            $browser->close();
        }

        $this->line(trim($link));
        $this->line(trim($img));
        $this->line(trim($title));
        $this->line(trim($teaser));
        $this->line(trim($text));
        $this->info('The command was successful!');
        return 0;
    }
}
