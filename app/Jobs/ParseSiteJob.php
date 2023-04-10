<?php

namespace App\Jobs;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use \App\Services\ParserService;


class ParseSiteJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $parser;
    public $timeout = 1000;

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
        $title = $this->parser->getSiteTitle();
        \Illuminate\Support\Facades\Log::debug($title);
        //Site::create(['title' => $title, 'url' => $this->parser->siteUrl]);
        //$pages = $this->parser->getSitePages();
    }
}
