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
    public function searchPages(Site $site = null, $high = false) {
        $queueName = $high === false ? 'searchpage' : 'searchpagehigh';
        if (isset($site))
            $sites = collect([$site]);
        else
            $sites = Site::all();

        foreach ($sites->all() as $site){
            SearchPagesJob::dispatch($site)->onQueue($queueName);
        }
    }
}
