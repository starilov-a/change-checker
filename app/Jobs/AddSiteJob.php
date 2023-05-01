<?php

namespace App\Jobs;

use App\Actions\SearchSitePageAction;
use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \App\Services\ParserService;


class AddSiteJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    public $timeout = 360;


    public function uniqueId()
    {
        return $this->url;
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SearchSitePageAction $action)
    {
        if(empty(Site::where('url', $this->url)->first())) {
            $parser = new ParserService($this->url);
            if(!$parser->isError()) {
                //Добавление сайта
                $title = $parser->getSiteTitle();
                $site = Site::create(['name' => $title, 'url' => $this->url]);

                //Поиск и добалвение страниц
                $action->searchPages($site);
            }
        }
    }
}
