<?php


namespace App\Actions;


use App\Jobs\ScanSiteAllJob;
use App\Jobs\ScanSiteMainJob;
use App\Models\Site;
use App\Services\ParserService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScanSiteAction
{
    public function scanSite($all = false) {
        $sites = Site::all();
        if ($all)
            foreach ($sites as $site){
                ScanSiteAllJob::dispatch(new ParserService($site->url), $site)->onQueue('scan');
            }
        else
            foreach ($sites as $site)
                ScanSiteMainJob::dispatch(new ParserService($site->url), $site)->onQueue('scan');

    }
}
