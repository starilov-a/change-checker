<?php


namespace App\Actions;


use App\Contracts\ScanSiteContract;
use App\Jobs\Scans\CheckSiteStatusJob;
use App\Jobs\Scans\ScanSiteJob;
use App\Models\Site;


class ScanSiteAction implements ScanSiteContract
{
    /**
     * Событие добавления сайта в очередь на сканирование страниц
     *
     * @return void
     */
    public function scanSites($siteData = []) {
        if (isset($siteData['id']))
            $sites = collect([Site::find($siteData['id'])]);
        else
            $sites = Site::all();

        foreach ($sites->all() as $site){
            CheckSiteStatusJob::dispatch($site)->onQueue('checksitestatus');
            ScanSiteJob::dispatch($site)->onQueue('scansite');
        }
    }


}
