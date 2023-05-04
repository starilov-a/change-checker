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
    protected $responseError = false;
    protected $fullSearch = true;
    protected $excludedUrls = [];
    public $siteUrl;

    public function __construct($url) {
        $this->siteUrl = str_replace('www.', '', rtrim(parse_url($url)['host']));
        $this->client = new Client(['base_uri' => $this->siteUrl, 'verify' => false]);
    }

    public function doRequest($path = '', $method = 'GET') {
        //TODO log ошибок
        $response = '';
        try {
            $response = mb_convert_encoding($this->client->request($method, $this->siteUrl.$path)->getBody(), "UTF8");
        } catch (TransferException $e) {
            $this->responseError = false;
        }
        return $response;
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
        return $this->responseError;
    }

    public function getSizePage($path, $response = null) {
        $size = 0;
        if (!isset($response))
            $response = $this->doRequest($path);

        $size = strlen($response);
        return $size;
    }

    protected function searchLinkPage($path, $pageUrls) {
        $response = $this->doRequest($path);
        $size = $this->getSizePage($path, $response);

        if ($this->isError() || $size == 0)
            return $pageUrls;

        $pageUrls[$path] = $size;

        if($this->fullSearch) {
            preg_match_all('/<a.*?href=["\'](.*?)["\'].*?>/i', $response, $matches);
            $paths = $this->formatterLinks($matches[1]);

            //освобождение памяти
            unset($matches);
            unset($response);
            unset($size);

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
            if (!isset($this->excludedUrls[$docLink])) {
                //удаление GET параметров, якорей и пробелов
                $link = explode('#', explode('?', trim($docLink))[0])[0];

                //в ссылке есть хост и он не является искомым
                if (!isset(parse_url($link)['host']) ||
                    (isset(parse_url($link)['host']) && strpos($link, $this->siteUrl) !== false)) {

                    //оставляем только path
                    $link = !empty(parse_url($link)['path']) ? parse_url($link)['path'] : '/';

                    //убираем специальные символы
                    if (strpos($link, './') === false &&
                        strpos($link, '../') === false &&
                        strpos($link, ';') === false &&
                        strpos($link, 'tel:') === false &&
                        strpos($link, 'mailto:') === false ) {

                        //добалвение первого слеша
                        if (strpos($link, '/') !== 0)
                            $link  = '/'.$link;

                        //пропуск страниц: new, блогов, страниц
                        preg_match('/\/[0-9]{2,4}\/[0-9]{2}\/[0-9]{2,4}\//', $link, $match);
                        if (strpos($link, '/news/') === false &&
                            strpos($link, '/page/') === false &&
                            strpos($link, '/category/') === false &&
                            strpos($link, '/author/') === false &&
                            strpos($link, '/publ/') === false &&
                            strpos($link, '/load/') === false &&
                            strpos($link, '/download_file/') === false &&
                            strpos($link, '/download/') === false &&
                            strpos($link, '/file/') === false &&
                            strpos($link, '/image/') === false &&
                            strpos($link, 'novosti') === false &&
                            empty($match)) {

                            //проверка на формат файла
                            if (strpos($link, '.') === false ||
                                strpos($link, '.php') !== false ||
                                strpos($link, '.html') !== false) {

                                $links[$link] = $link;
                                continue;
                            }
                        }
                    }
                }
            }
            //если не проходит хоть одно условие, то добавляется в исключения
            $this->excludedUrls[$docLink] = true;
        }
        return $links;
    }

    protected function searchTitle() {
        preg_match('/<title[^>]*?>(.*?)<\/title>/si', $this->doRequest('/'), $matches);

        if (!empty($matches))
            $title = $matches[1];
        else
            $title = $this->siteUrl;

        return $title;
    }

}
