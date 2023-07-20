<?php


namespace App\Services\Parser\Clients;

use App\Services\Parser\Response\HttpResponse;
use App\Services\Parser\Response\HttpResponseInterface;

class CurlClient implements ClientInterface
{
    protected $curl;

    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt($this->curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($this->curl,CURLOPT_TIMEOUT, 30);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1);
        //помогает обойти 403 ошибку на некоторых сайтах
        curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    }

    public function request($path, $method = 'GET', $opt = []): HttpResponseInterface {
        $body = '';

        curl_setopt($this->curl,CURLOPT_URL, $path);
        if ($method == 'POST' && isset($opt['param'])) {
            foreach($opt['param'] as $key=>$value)
                $vars .= $key . "=" . $value . "&";
            curl_setopt($this->curl,CURLOPT_POSTFIELDS, $vars);
        }
        if ($method == 'POST') {
            curl_setopt($this->curl,CURLOPT_POST, 1);
        }

        //TODO посмотреть что будет если 504 в теле

        $body = curl_exec($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_RESPONSE_CODE);
        $code = ($code === 0) ? 504 : $code;

        return new HttpResponse($body, $code);
    }
}
