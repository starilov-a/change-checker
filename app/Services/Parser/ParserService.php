<?php

namespace App\Services\Parser;

use App\Services\Parser\Clients\ClientInterface;
use App\Services\Parser\Response\HttpResponseInterface;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class ParserService
{

    use SerializesModels;

    protected $client;
    protected $responseError = false;
    protected $siteUrl;
    protected $origUrl;

    public function __construct($url) {
        $this->siteUrl = $this->indexFormatter($url);
        $this->client = resolve(ClientInterface::class); // связывание через контейнер
        $this->origUrl = $url;
    }

    public function parse($path = ''): HttpResponseInterface {
        $response = $this->client->request($this->siteUrl.$path);
        $this->responseError = $response->getStatusCode() >= 400;
        return $response;
    }

    public function isError() {
        return $this->responseError;
    }

    private function indexFormatter($url) {
        return str_replace('www.', '', rtrim(parse_url($url)['host']));
    }
}
