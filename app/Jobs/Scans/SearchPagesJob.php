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

class SearchPagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $site;
    protected $bufferId;
    public $timeout = 4800;

    public function uniqueId()
    {
        return $this->site->url;
    }
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Site $site, $bufferId = false)
    {
        $this->site = $site;
        $this->bufferId = $bufferId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $parser = new ParserEduService($this->site->url);
        $pages = $parser->getSitePages('/', $this->bufferId);

        $this->site->page_count = count($pages);
        $this->site->save();

        foreach ($pages as $path => $size) {
            $this->site->pages()->firstOrCreate([
                'site_id' => $this->site->id,
                'url' => $path
            ],[
                'size' => $size,
            ]);
        }
    }
}
