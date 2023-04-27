<?php


namespace App\Actions;


use App\Contracts\ScanSiteContract;
use App\Jobs\Scans\ScanSiteIndexJob;
use App\Models\Site;


class ScanSiteAction implements ScanSiteContract
{
    public function scanSites($siteId = false)
    {
        if ($siteId !== false)
            $sites[] = Site::where($siteId)->get();
        else
            $sites = Site::all();

        foreach ($sites as $site){
            //TODO изменить на ScanSiteAllJob
            ScanSiteIndexJob::dispatch($site)->onQueue('scan');
        }
    }


}
