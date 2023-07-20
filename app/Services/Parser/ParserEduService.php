<?php


namespace App\Services\Parser;




use App\Jobs\Scans\SearchPagesJob;
use App\Models\Site;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ParserEduService extends ParserService
{
    //номер итерации
    protected $i = 0;

    //кол-во иттераций рекурсивного посика(кол-во страниц за раз)
    protected $countItr = 2500;

    //исключенные страницы
    protected $excludedUrls = [];

    //Все урлы и веса страниц которые прошел парсер
    protected $pageUrls = [];

    //Все урлы которые пройдет парсер
    protected $paths = [];

    //site
    protected $site;

    public function getSiteStatus($path = '/') {
        $response = $this->parse($path);
        return $response->getStatusCode();
    }

    public function getSiteTitle() {
        preg_match('/<title[^>]*?>(.*?)<\/title>/si', $this->parse('/')->getBody(), $matches);
        return !empty($matches) ? $matches[1] : $this->siteUrl;
    }

    public function getSizePage($path) {
        return strlen($this->parse($path)->getBody());
    }

    public function getSitePages($path = '/', $bufferId = false) {
        if ($bufferId === false) {
            $this->i++;
            if ($this->isMany()){
                $this->continueSearch();
                return $this->pageUrls;
            }
            $response = $this->parse($path);

            if ($this->isError())
                return $this->pageUrls;

            $body = $response->getBody();
            $status = $response->getStatusCode();
            $size = strlen($body);

            $this->pageUrls[$path]['size'] = $size;
            $this->pageUrls[$path]['statusCode'] = $status;

            preg_match_all('/<a[\W|.*?]href=["\']{0,1}(.*?)["\']{0,1}[\W|.*?]*?>/i', $body, $matches);
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

        return $this->pageUrls;
    }

    protected function formatterLinks($docLinks) {
        $links = [];
        foreach ($docLinks as $docLink) {
            if (!isset($this->excludedUrls[$docLink])) {

                //есть ли урл в полученной сслыке
                $UrlInLink = strpos($docLink, $this->siteUrl) !== false;

                if (strpos($docLink, 'https://') === false && strpos($docLink, 'http://') && $UrlInLink)
                    $docLink = 'http://'.$docLink;
                $linkBits= parse_url($docLink);
                //в ссылке есть хост и он не является искомым
                if (!isset($linkBits['host']) ||
                    (isset($linkBits['host']) && $UrlInLink)
                ) {
                    if(empty($linkBits['path']))
                        $linkBits['path'] = '/';

                    //удаление GET параметров, якорей и пробелов
                    //оставляем только path
                    if (!empty($linkBits['query'])) {
                        $link = $linkBits['path'] . '?' . $linkBits['query'];
                        $link = (strpos($link, '?page_id=') === 1 || strpos($link, '?p=') === 1) ?
                            explode('&', $link)[0] :
                            $linkBits['path'];
                    } else {
                        $link = $linkBits['path'];
                    }
                    //убираем специальные символы
                    if (strpos($link, './') === false &&
                        strpos($link, '../') === false &&
                        strpos($link, ';') === false &&
                        strpos($docLink, 'tel:') === false &&
                        strpos($docLink, 'mailto:') === false ) {

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
                                strpos($link, '.htm') !== false ||
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
        SearchPagesJob::dispatch(Site::where('url', $this->origUrl)->first(), $bufferId)->onQueue('searchpage');
    }

    protected function saveBufferData() {
        $data = json_encode([
            'siteUrl' => $this->siteUrl,
            'pageUrls' => $this->pageUrls,
            'paths' => $this->paths,
            'excludedUrls' => $this->excludedUrls
        ]);

        return DB::table('parser_page_buffer')->insertGetId([
            'data' => $data,
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now(),
        ]);
    }

    protected function loadBufferData($bufferId) {
        $data = json_decode(DB::table('parser_page_buffer')->find($bufferId)->data);
        DB::table('parser_page_buffer')->delete($bufferId);
        $this->paths = (array) $data->paths;
        $this->excludedUrls = (array) $data->excludedUrls;
        $this->pageUrls = (array) $data->pageUrls;
    }
}
