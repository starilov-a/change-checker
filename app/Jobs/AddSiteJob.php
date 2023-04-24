<?php

namespace App\Jobs;

use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \App\Services\ParserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class AddSiteJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    public $timeout = 1000;


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
    public function handle()
    {
        if(empty(Site::where('url', $this->url)->first())) {
            $parser = new ParserService($this->url);
            if(!$parser->isError()) {
                $title = $parser->getSiteTitle();
                $site = Site::create(['name' => $title, 'url' => $this->url]);

                $insertArr = [];
                $pages = $parser->getSitePages();

                $site->page_count = count($pages);
                $site->save();

                foreach ($pages as $path => $size) {
                    $insertArr[] = [
                        'site_id' => $site->id,
                        'url' => $path,
                        'size' => $size,
                        'created_at' => Carbon::now()
                    ];
                }
                DB::table('pages')->insert($insertArr);
            }
        }
    }
}
