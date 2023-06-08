<?php

namespace App\Jobs\Scans;

use App\Models\Page;
use App\Models\Site;
use App\Services\ParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
    protected $page;
    public $timeout = 360;

    public function uniqueId()
    {
        return $this->site->url;
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Site $site, Page $page)
    {
        $this->site = $site;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $parser = new ParserService($this->site->url);
        $page = $this->page;

        //TODO сделать поиск страницы либо исключить отсутствия страниц
        if ($page === null)
            return false;
        //получить вес страницы
        $size = $page->size;
        //сделать запрос и получить еще один вес страницы
        $newSize = $parser->getSizePage($page->url);
        //сравнить
        if (($size - 20) > $newSize || ($size + 20) < $newSize) {
            //запись в таблицы
            $page->size = $newSize;
            $page->save();

            $this->site->changes()->updateOrCreate([
                'site_id' => $this->site->id,
                'url' => $page->url
            ],[
                'checked' => false
            ]);
        }
    }
}
