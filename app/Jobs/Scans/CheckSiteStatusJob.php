<?php

namespace App\Jobs\Scans;

use App\Models\Site;
use App\Services\Parser\ParserEduService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckSiteStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
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
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parser = new ParserEduService($this->site->url);
        $statusSite = $parser->getSiteStatus();

        $this->site->status_code = $statusSite;
        $this->site->save();
    }
}
