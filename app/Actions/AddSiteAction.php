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
            $url = parse_url($url)['host'] ?? $url;
            AddSiteJob::dispatch('http://'.$url)->onQueue('addsite');
        }
    }
}
