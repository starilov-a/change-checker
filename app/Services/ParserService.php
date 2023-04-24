<?php

namespace App\Services;

use Drnxloc\LaravelHtmlDom\HtmlDomParser;

class ParserService
{
    protected $ch;
    protected $response;
    protected $fullSearch = false;
    public $siteUrl;

    public function __construct($url) {
        //TODO Проверять только корневой путь
        $this->siteUrl = rtrim($url,'/');
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);

        $this->response = curl_exec($this->ch);
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

    public function getSizePage($path, $response = null) {
        $size = 0;
        $newRespon = false;
        if (!isset($response)){
            curl_setopt($this->ch, CURLOPT_URL, $this->siteUrl.$path);
            $response = curl_exec($this->ch);
        }

        $size = strlen($response);
        return $size;
    }

    protected function searchLinkPage($path, $pageUrls) {
        curl_setopt($this->ch, CURLOPT_URL, $this->siteUrl.$path);
        $response = curl_exec($this->ch);
        $pageUrls[$path] = $this->getSizePage($path, $response);

        if($this->fullSearch) {
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
        $dom = HtmlDomParser::str_get_html($this->response);

        $titlesArr = $dom->find('title');
        if (!empty($titlesArr))
            $title = $titlesArr[0]->plaintext;
        else
            $title = $this->siteUrl;

        return $title;
    }

}
