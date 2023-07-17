<?php

namespace App\Services\Parser;

use App\Services\GuzzleMonitor\MonitorClient;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Queue\SerializesModels;

abstract class ParserService
{

    use SerializesModels;

    protected $client;
    protected $responseError = false;
    protected $responseCode;
    protected $siteUrl;

    public function __construct($url) {
        $this->siteUrl = $this->indexFormatter($url);
        $this->client = new MonitorClient(['base_uri' => $this->siteUrl, 'verify' => false]);
    }

    public function request($path = '', $method = 'GET', $opt = []) {
        $response = '';
        try{
            $response = $this->client->request($method, $this->siteUrl.$path, $opt);
        } catch (TransferException $e) {
            $this->responseCode = 504;
            $this->responseError = true;
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
