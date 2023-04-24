<?php


namespace App\Actions;


use App\Contracts\AddSiteContract;
use App\Jobs\AddSiteJob;

class AddSiteAction implements AddSiteContract
{
    public function addSites($urls) {
        foreach ($urls as $url) {
            if (strpos($url, 'http') !== 0)
                $url = 'http://'.$url;

            AddSiteJob::dispatch($url)->onQueue('addsite');
        }
    }
}
