<?php


namespace App\Services\Parser;




use App\Jobs\Scans\SearchPagesJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParserEduService extends ParserService
{
    //номер итерации
    protected $i = 0;

    //кол-во иттераций рекурсивного посика(кол-во страниц за раз)
    protected $countItr = 2500;

    //домашняя страница либо поиск по всему сайту
    protected $fullSearch = true;

    //исключенные страницы
    protected $excludedUrls = [];

    //Все урлы и веса страниц которые прошел парсер
    protected $pageUrls = [];

    //Все урлы которые пройдет парсер
    protected $paths = [];

    //site
    protected $site;

    public function __construct($url, $site = null) {
        parent::__construct($url);
        $this->site = $site;
    }

    public function getSiteStatus($path = '/') {
        $response = $this->request($path, 'GET', ['connect_timeout' => 5, 'http_errors' => false]);
        if (!empty($response))
            $code = $response->getStatusCode();
        else
            $code = $this->responseCode;
        return $code;
    }

    public function getSiteTitle() {
        preg_match('/<title[^>]*?>(.*?)<\/title>/si', $this->request('/')->getBody(), $matches);
        return !empty($matches) ? $matches[1] : $this->siteUrl;
    }

    public function getSizePage($path) {
        return strlen($this->request($path)->getBody());
    }

    public function getSitePages($path = '/', $bufferId = false) {
        $this->i++;
        if ($this->isMany()){
            $this->continueSearch();
            return $this->pageUrls;
        }

        $response = $this->request($path);

        $body = $response->getBody();
        $status = $response->getStatusCode();
        $size = strlen($body);

        if ($this->isError() || $size === 0)
            return $this->pageUrls;

        $this->pageUrls[$path]['size'] = $size;
        $this->pageUrls[$path]['statusCode'] = $status;

        if($this->fullSearch) {
            if ($bufferId === false) {
                preg_match_all('/<a.*?href=["\'](.*?)["\'].*?>/i', $body, $matches);
                $paths = $this->formatterLinks($matches[1]);

                //освобождение памяти
                unset($matches);
                unset($body);
                unset($size);

                //buffer unused
                foreach ($paths as $path)
                    if (!isset($this->paths[$path]))
                        $this->paths[$path] = $path;
            } else {
                $this->loadBufferData($bufferId);
            }

            foreach ($this->paths as $path){
                if($this->isMany())
                    return $this->pageUrls;

                if (!isset($this->pageUrls[$path]))
                    $this->pageUrls = $this->getSitePages($path);
            }
        }
        return $this->pageUrls;
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
                        if (//strpos($link, '/news/') === false &&
                            //strpos($link, '/page/') === false &&
                            //strpos($link, '/category/') === false &&
                            //strpos($link, '/author/') === false &&
                            //strpos($link, '/publ/') === false &&
                            strpos($link, '/load/') === false &&
                            strpos($link, '/download_file/') === false &&
                            strpos($link, '/download/') === false &&
                            strpos($link, '/file/') === false &&
                            strpos($link, '/image/') === false &&
                            //strpos($link, 'novosti') === false &&
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

    public function isMany() {
        return $this->i >= $this->countItr;
    }

    public function continueSearch() {
        $bufferId = $this->saveBufferData();
        SearchPagesJob::dispatch($this->site, $bufferId)->onQueue('searchpage');
    }

    protected function saveBufferData() {
        $data = json_encode([
            'siteUrl' => $this->siteUrl,
            'pageUrls' => $this->pageUrls,
            'paths' => $this->paths,
            'excludedUrls' => $this->excludedUrls
        ]);

        return DB::table('parser_page_buffer')->insertGetId(['data' => $data]);
    }

    protected function loadBufferData($bufferId) {
        $data = json_decode(DB::table('parser_page_buffer')->find($bufferId)->data);
        DB::table('parser_page_buffer')->delete($bufferId);
        $this->paths = (array) $data->paths;
        $this->excludedUrls = (array) $data->excludedUrls;
        $this->pageUrls = (array) $data->pageUrls;
    }
}
