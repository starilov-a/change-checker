<?php

namespace App\Jobs;

use App\Models\Page;
use App\Models\Site;
use App\Models\User;
use App\Notifications\FindChange;
use App\Services\ParserService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScanSiteMainJob implements ShouldQueue
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
        Log::debug($site);
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
        $mainPage = Page::where('url', '/')->where('site_id', $this->site->id)->first();
        //получить вес страницы
        $size = $mainPage->size;
        //сделать запрос и получить еще один вес страницы
        $newSize = $this->parser->getSizePage($mainPage->url);
        //сравнить
        Log::debug($size);Log::debug($newSize);
        if ($size != $newSize) {
            //запись в таблицы
            $this->site->check = false;
            $this->site->save();

            $mainPage->size = $newSize;
            $mainPage->save();

            DB::table('changes')->insert([
                'site_id' => $this->site->id,
                'url' => '/',
                'created_at' => Carbon::now()
            ]);
            //notificate

            Notification::send(User::where('name', 'admin')->first(), new FindChange($this->site));
        }


    }
}
