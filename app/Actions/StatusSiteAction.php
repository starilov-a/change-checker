<?php


namespace App\Actions;


use App\Jobs\Scans\CheckSiteStatusJob;
use App\Models\Site;


class StatusSiteAction
{
    /**
     * Событие поиска новых страниц сайта
     *
     * @return void
     */
    public function checkSiteStatus(Site $site = null) {
        if (isset($site))
            $sites = collect([$site]);
        else
            $sites = Site::all();

        foreach ($sites->all() as $site){
            CheckSiteStatusJob::dispatch($site)->onQueue('checksitestatus');
        }
    }
}
