<?php

namespace App\Services;

use Drnxloc\LaravelHtmlDom\HtmlDomParser;
use Illuminate\Support\Facades\Http;

class ParserService
{
    protected $http;
    protected $fullSearch = false;
    public $siteUrl;

    public function __construct($url) {
        //TODO Проверять только корневой путь
        $this->siteUrl = rtrim($url,'/');
        $this->http = new Http();
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

    public function getSizePage($path, $response = null) {
        $size = 0;
        $newRespon = false;

        if (!isset($response)){
            $response = $this->http::head($this->siteUrl.$path);
            $newRespon = true;
        }

        $headers = $response->headers();
        if (isset($headers['Content-Length'][0])) {
            $size = $headers['Content-Length'][0];
        } else {
            if ($newRespon)
                $response = $this->http::get($this->siteUrl.$path);
            $size = strlen($response->body());
        }
        return $size;
    }

    protected function searchLinkPage($path, $pageUrls) {
        $response = $this->http::get($this->siteUrl.$path);
        $pageUrls[$path] = $this->getSizePage($path, $response);

        if($this->fullSearch) {
            $dom = HtmlDomParser::str_get_html($response->body());
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
        $response = $this->http::get($this->siteUrl);
        $dom = HtmlDomParser::str_get_html($response->body());
        $title = $dom->find('title')[0]->plaintext ?? $this->siteUrl;
        return $title;
    }

}
