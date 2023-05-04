<?php


namespace App\Actions;


use App\Contracts\AddSiteContract;
use App\Jobs\AddSiteJob;
use Illuminate\Support\Facades\Log;

class AddSiteAction implements AddSiteContract
{
    /**
     * Событие добавления сайтов в систему
     *
     * @return void
     */
    public function addSites($urls) {
        foreach ($urls as $url) {
            $url = 'http://'.parse_url($url)['host'];
            AddSiteJob::dispatch($url)->onQueue('addsite');
        }
    }
}
