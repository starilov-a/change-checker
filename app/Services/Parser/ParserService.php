<?php

namespace App\Services\Parser;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

abstract class ParserService
{

    use SerializesModels;

    protected $client;
    protected $responseError = false;
    protected $siteUrl;

    public function __construct($url) {
        $this->siteUrl = $this->indexFormatter($url);
        $this->client = new Client(['base_uri' => $this->siteUrl, 'verify' => false]);
    }

    public function request($path = '', $method = 'GET') {
        //TODO log ошибок
        $response = '';
        try {
            $response = mb_convert_encoding($this->client->request($method, $this->siteUrl.$path)->getBody(), "UTF8");
        } catch (TransferException $e) {
            $this->responseError = false;
        }
        return $response;
    }

    public function isError() {
        return $this->responseError;
    }

    private function indexFormatter($url) {
        return str_replace('www.', '', rtrim(parse_url($url)['host']));
    }
}
