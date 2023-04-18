<?php


namespace App\Actions;


use App\Contracts\AddSiteContract;
use App\Jobs\AddSiteJob;
use App\Services\ParserService;
use Illuminate\Support\Facades\Http;

class AddSiteAction implements AddSiteContract
{
    public function addSites($urls) {
        foreach ($urls as $url) {
            AddSiteJob::dispatch(new ParserService($url))->onQueue('addsite');
        }
    }
}
