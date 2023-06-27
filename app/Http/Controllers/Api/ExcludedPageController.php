<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\SiteExcludedCollection;
use App\Models\ExcludedPage;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\Request;
class ExcludedPageController extends Controller
{
    /**
     * * @OA\Tag(
     *     name="excludedpages",
     *     description="Исключенные страницы"
     * )
     * @OA\Get(
     *     path="/excludedpages",
     *     tags={"excludedpages"},
     *     summary="Список исключений",
     *     @OA\Parameter(
     *         name="site_id",
     *         in="query",
     *         description="Id сайта(если не указывать, то прийдет всё)",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="start(0)",
     *         in="query",
     *         description="Отсчет для пагинации",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit(20)",
     *         in="query",
     *         description="Лимит пагинации",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="data",
     *                type="array",
     *                example={{
     *                  "id": 5762,
     *                  "page_id": 1234,
     *                },{
     *                  "id": "...",
     *                  "page_id": "...",
     *                }},
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="page_id",
     *                         type="int"
     *                      ),
     *                 ),
     *             ),
     *             @OA\Property(
     *                property="meta",
     *                type="array",
     *                example={
     *                  "count": 10000,
     *                  "start": 0,
     *                  "limit": 20,
     *                },
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="count",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="start",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="limit",
     *                         type="int"
     *                      ),
     *                 ),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ErrorResult401"),
     *         )
     *     )
     * )
     */
    public function index(\App\Http\Requests\ListRequest $request)
    {
        $request->validated();

        $start = $request->input('start', 0);
        $limit = $request->input('limit', 100);

        return PageResource::collection(Page::onlyExcluded($request->site_id)->skip($start)->take($limit)->get());
    }

    /**
     * @OA\Get(
     *     path="/excludedpages/sites",
     *     tags={"excludedpages"},
     *     summary="Список исключенных сайтов",
     *     @OA\Parameter(
     *         name="start(0)",
     *         in="query",
     *         description="Отсчет для пагинации",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="limit(20)",
     *         in="query",
     *         description="Лимит пагинации",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="data",
     *                type="array",
     *                example={{
     *                  "id": 5762,
     *                  "name": 1234,
     *                  "url": 1234,
     *                  "page_count": 1234,
     *                },{
     *                  "id": "...",
     *                  "name": "...",
     *                  "url": "...",
     *                  "page_count": "...",
     *                }},
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="name",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="url",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="page_count",
     *                         type="int"
     *                      ),
     *                 ),
     *             ),
     *             @OA\Property(
     *                property="meta",
     *                type="array",
     *                example={
     *                  "count": 10000,
     *                  "start": 0,
     *                  "limit": 20,
     *                },
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="count",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="start",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="limit",
     *                         type="int"
     *                      ),
     *                 ),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ErrorResult401"),
     *         )
     *     )
     * )
     */
    public function indexsite(\App\Http\Requests\PaginationRequest $request)
    {
        $request->validated();

        $start = $request->input('start', 0);
        $limit = $request->input('limit', 100);

        return (new SiteExcludedCollection(Site::has('excludedPages')->skip($start)->take($limit)->get()))->additional(['meta' => ['start' => $start, 'limit' => $limit]]);
    }

    /**
     * @OA\Post(
     *     path="/excludedpages",
     *     tags={"excludedpages"},
     *     summary="Добавление страницы в исключение",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="page_id",
     *                     type="int"
     *                 ),
     *                 example={"page_id": 123}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 example={
     *                   "message": "Страница добавлена в исключения"
     *                 },
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Не авторизован",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/ErrorResult401"),
     *         )
     *     )
     * )
     */
    public function store(\App\Http\Requests\ExcludedPageStoreRequest $request)
    {
        $request->validated();
        $page = Page::with('site')->find($request->page_id);

        ExcludedPage::create(['site_id' => $page->site->id, 'page_id' => $page->id]);

        return response(['data' => ['message' => 'Страница добавлена в исключения']], \Illuminate\Http\Response::HTTP_OK);
    }
}
