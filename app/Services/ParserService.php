<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Queue\SerializesModels;

class ParserService
{

    use SerializesModels;

    protected $client;
    protected $response;
    protected $fullSearch = true;
    public $siteUrl;

    public function __construct($url) {
        $this->siteUrl = str_replace('www.', '', rtrim(parse_url($url)['host']));
        $this->client = new Client(['base_uri' => $this->siteUrl, 'verify' => false]);
        $this->doRequest();
    }

    public function doRequest($path = '', $method = 'GET') {
        //TODO log ошибок
        $this->response = '';
        try {
            $this->response = mb_convert_encoding($this->client->request($method, $this->siteUrl.$path)->getBody(), "UTF8");
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
        $response = $this->doRequest($path);
        $size = strlen($response);
        return $size;
    }

    protected function searchLinkPage($path, $pageUrls) {
        $response = $this->doRequest($path);
        $pageUrls[$path] = $this->getSizePage($path);

        if($this->fullSearch) {
            preg_match_all('/<a.*?href=["\'](.*?)["\'].*?>/i', $response, $matches);
            $paths = $this->formatterLinks($matches[1]);
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
            //удаление GET параметров и пробелов
            $link = explode('?', trim($docLink))[0];
            //ссылка на текущйи сайт?
            if ((!isset(parse_url($link)['host']) || strpos($link, $this->siteUrl) !== false) && strpos($link, 'tel:') === false) {
                //если пустая ссылка, то корневой сайт
                $link = parse_url($link)['path'] ?? '/';
                //проверка на формат
                if (strpos($link, '.') === false || strpos($link, '.php') !== false || strpos($link, '.html') !== false) {
                    $link = parse_url($link)['path'];
                    $links[$link] = $link;
                }
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
