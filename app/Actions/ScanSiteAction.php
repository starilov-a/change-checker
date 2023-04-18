<?php


namespace App\Actions;


use App\Jobs\ChangeNotificateJob;
use App\Jobs\Scans\ScanSiteIndexJob;
use App\Models\Site;
use App\Services\ParserService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScanSiteAction
{
    public function scanSite($siteId = null) {
        if (!empty($siteId))
            $sites[] = Site::where($siteId)->get();
        else
            $sites = Site::all();

        foreach ($sites as $site){
            //TODO изменить на ScanSiteAllJob
            ScanSiteIndexJob::dispatch(new ParserService($site->url), $site)->onQueue('scan ');
        }
    }
}
