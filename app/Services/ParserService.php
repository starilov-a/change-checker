<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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

        if ($response === false)
            return $pageUrls;

        if($this->fullSearch) {
            preg_match_all('/<a.*?href=["\'](.*?)["\'].*?>/i', $response, $matches);
            $paths = $this->formatterLinks($matches[1]);

            //освобождение памяти
            unset($matches);

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
            //удаление GET параметров, якорей и пробелов
            $link = explode('#', explode('?', trim($docLink))[0])[0];

            //в ссылке есть хост и он не является искомым
            if (isset(parse_url($link)['host']) && strpos($link, $this->siteUrl) === false)
                continue;

            //оставляем только path
            $link = !empty(parse_url($link)['path']) ? parse_url($link)['path'] : '/';

            //убираем специальные символы
            if (strpos($link, './') === 0 &&
                strpos($link, '../') === 0 &&
                strpos($link, ';') !== false &&
                strpos($link, 'tel:') !== false)
                continue;

            //проверка на формат файла
            if (strpos($link, '.') !== false &&
                (strpos($link, '.php') === false || strpos($link, '.html') === false))
                continue;

            $links[$link] = $link;
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
