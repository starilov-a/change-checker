<?php

namespace App\Jobs\Scans;

use App\Models\Page;
use App\Services\ParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanSiteIndexJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $parser;
    protected $site;

    public function uniqueId()
    {
        return $this->parser->siteUrl;
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ParserService $parser, $site)
    {
        $this->parser = $parser;
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mainPage = Page::where('url','=', '/')->where('site_id', '=', $this->site->id)->first();

        //TODO сделать поиск страницы либо исключить отсутствия страниц
        if ($mainPage === null)
            return false;
        //получить вес страницы
        $size = $mainPage->size;
        //сделать запрос и получить еще один вес страницы
        $newSize = $this->parser->getSizePage($mainPage->url);
        //сравнить
        if ($size != $newSize) {

            //запись в таблицы
            $mainPage->size = $newSize;
            $mainPage->save();

            $this->site->changes()->updateOrCreate([
                'site_id' => $this->site->id,
                'url' => '/'
            ],[
                'checked' => true
            ]);
        }
    }
}
