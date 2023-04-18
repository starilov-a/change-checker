<?php

namespace App\Jobs;

use App\Models\Page;
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

    protected $parser;
    public $timeout = 1000;


    public function uniqueId()
    {
        return $this->parser->siteUrl;
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ParserService $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty(Site::where('url', $this->parser->siteUrl)->first())) {
            $title = $this->parser->getSiteTitle();
            $site = Site::create(['name' => $title, 'url' => $this->parser->siteUrl]);

            $insertArr = [];
            $pages = $this->parser->getSitePages();

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
