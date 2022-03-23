<?php

namespace App\Console\Commands;

use HeadlessChromium\Page;
use Illuminate\Console\Command;
use HeadlessChromium\BrowserFactory;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

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
        $browserFactory->addOptions(['noSandbox' => true, 'headless' => false]);
        $browser = $browserFactory->createBrowser();
        try {
                $page = $browser->createPage();
                $page->navigate('http://www.example.com')->waitForNavigation(Page::DOM_CONTENT_LOADED, 60000);
                $this->line('Connected');
        } finally {
            // bye
            $browser->close();
        }
        return 0;
    }
}
