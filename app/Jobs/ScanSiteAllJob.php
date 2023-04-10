<?php

namespace App\Jobs;

use App\Services\ParserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScanSiteAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        //
    }
}
