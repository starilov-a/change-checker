<?php

namespace App\Services;

use Drnxloc\LaravelHtmlDom\HtmlDomParser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ParserService
{

    use SerializesModels;

    protected $client;
    protected $response;
    protected $fullSearch = false;
    public $siteUrl;

    public function __construct($url) {
        //TODO Проверять только корневой путь
        $this->siteUrl = rtrim($url,'/');
        $this->client = new Client(['base_uri' => $this->siteUrl, 'verify' => false]);
        $this->doRequest();
    }

    public function doRequest($path = '', $method = 'GET') {
        try {
            $this->response = mb_convert_encoding($this->client->request($method)->getBody(), "UTF8");;
        } catch (TransferException $e) {
            $this->response = false;
        }
        return $this->response;
    }



    public function getSitePages() {
        $pages = [];
        $pageUrls = [];
        $pages = $this->searchLinkPage('/', $pageUrls);
        return $pages;
    }

    public function getSiteTitle() {
        return $this->searchTitle();
    }

    public function isError() {
        //TODO Доделать логи ошибок
        if ($this->response == false)
            return true;
        return false;
    }

    public function getSizePage($path) {
        $size = 0;
        if (!isset($this->response)){
            $this->doRequest($path);
        }

        $size = strlen($this->response);
        return $size;
    }

    protected function searchLinkPage($path, $pageUrls) {
        $response = $this->doRequest($path);
        $pageUrls[$path] = $this->getSizePage($path);

        if($this->fullSearch) {
            //TODO поменять на preg
            $dom = HtmlDomParser::str_get_html($response);
            $paths = $this->formatterLinks($dom->find('a'));

            foreach ($paths as $path) {
                if (!isset($pageUrls[$path])) {
                    $pageUrls = $this->searchLinkPage($path, $pageUrls);
                }
            }
        }
        return $pageUrls;
    }

    protected function formatterLinks($docLinks) {
        $links = [];
        foreach ($docLinks as $docLink) {
            $link = trim($docLink->href);

            if (!empty($link) &&
                ($link[0] === '/' || strpos($link, $this->siteUrl) === 0) &&
                (strpos($link, '.') === false || strpos($link, '.php') !== false || strpos($link, '.html') !== false)
            ) {
                $link = str_replace($this->siteUrl, '', $link);
                $links[$link] = $link;
            }
        }
        return $links;
    }

    protected function searchTitle() {
        preg_match('/<title[^>]*?>(.*?)<\/title>/si', $this->response, $matches);

        if (!empty($matches))
            $title = $matches[1];
        else
            $title = $this->siteUrl;

        return $title;
    }

}
