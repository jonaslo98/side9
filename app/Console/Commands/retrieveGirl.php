<?php

namespace App\Console\Commands;

use App\Models\Girl;
use HeadlessChromium\Page;
use Illuminate\Console\Command;
use HeadlessChromium\BrowserFactory;

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
        $browserFactory->addOptions(['noSandbox' => true]);
        $browser = $browserFactory->createBrowser();
        try {
            $page = $browser->createPage();
            $page->navigate('http://www.seoghoer.dk/tags/se-og-hoer-pigen')->waitForNavigation(Page::DOM_CONTENT_LOADED, 60000);
            $this->line('Connected');
            $elem = $page->dom()->querySelector('.sh-teaser__blocklink')->getAttribute('href');
            $page->navigate('https://www.seoghoer.dk' . $elem)->waitForNavigation(Page::DOM_CONTENT_LOADED, 40000);
            $link = 'http://www.seoghoer.dk' . $elem;
            $img = $page->dom()->querySelector('.img__thumbnail')->getAttribute('src');
            $array = explode('?', $img);
            $img = $array[0];
            $title = $page->dom()->querySelector('.article-title')->getText();
            $teaser = $page->dom()->querySelector('.article-teaser')->getText();
            $text = $page->evaluate('let f = function() {
  let text = ""
  let node = document.querySelectorAll("div.content-body > p")
  node.forEach(v => {
    text = text + " " + v.innerText
  })
  return text
}; f();')->getReturnValue();
            $this->line("Found text");

        } finally {
            // bye
            $browser->close();
        }

        $girl = new Girl();
        $girl->text = trim($text);
        $girl->link = trim($link);
        $girl->file_path = $img;
        $girl->title = trim($title);
        $girl->teaser = trim($teaser);
        $girl->save();
        $this->line(($girl->wasRecentlyCreated) ? "Girl added" : "Fail");
        return 0;
    }
}
