<?php


namespace App\Actions;


use App\Jobs\Scans\SearchPagesJob;
use App\Models\Site;


class SearchSitePageAction
{
    /**
     * Событие поиска новых страниц сайта
     *
     * @return void
     */
    public function searchPages($siteData) {

        if (isset($siteData['id']))
            $sites = collect([Site::find($siteData['id'])]);
        else
            $sites = Site::all();

        foreach ($sites->all() as $site){
            SearchPagesJob::dispatch($site)->onQueue('searchpage');
        }
    }
}
