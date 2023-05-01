<?php


namespace App\Actions;


use App\Contracts\AddSiteContract;
use App\Jobs\AddSiteJob;

class AddSiteAction implements AddSiteContract
{
    /**
     * Событие добавления сайтов в систему
     *
     * @return void
     */
    public function addSites($urls) {
        foreach ($urls as $url) {
            if (strpos($url, 'http') !== 0)
                $url = 'http://'.$url;

            AddSiteJob::dispatch($url)->onQueue('addsite');
        }
    }
}
