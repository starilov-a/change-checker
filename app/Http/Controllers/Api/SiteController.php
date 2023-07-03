<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AddSiteContract;
use App\Http\Resources\SiteResource;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * * @OA\Tag(
     *     name="sites",
     *     description="Сайты"
     * )
     * @OA\Get(
     *     path="/sites",
     *     tags={"sites"},
     *     summary="Список сайтов",
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
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="data",
     *                type="array",
     *                example={{
     *                  "id": 5762,
     *                  "name": "ExampleSite",
     *                  "url": "examplesite.com",
     *                  "page_count": 99,
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

    public function index(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 20);

        return SiteResource::collection(Site::skip($start)->take($limit)->orderBy('created_at', 'desc')->get());
    }

    /**
     * @OA\Post(
     *     path="/sites",
     *     tags={"sites"},
     *     summary="Добавлените сайтов",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="urls",
     *                example={
     *                  "site1.com","site2.com","..."
     *                },
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 example={
     *                   "message": "Запрос на добавление и парсинг сайтов создан"
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

    public function store(\App\Http\Requests\SiteStoreRequest $request, AddSiteContract $action)
    {
        $urls = $request->validated()['urls'];
        $action->addSites($urls);
        return response(['data' => ['message' => 'Запрос на добавление и парсинг сайтов создан']], \Illuminate\Http\Response::HTTP_OK);
    }

    /**
     * @OA\Get(
     *     path="/sites/{site_id}",
     *     tags={"sites"},
     *     summary="Получение сайта с его страницами",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 example={{
     *                   "id": 5762,
     *                   "name": "ExampleSite",
     *                   "url": "examplesite.com",
     *                   "page_count": 99,
     *                   "pages": {{
     *                                "id": 637347,
     *                                "url": "/",
     *                                "size": 1323,
     *                            },{
     *                                "id": "...",
     *                                "url": "...",
     *                                "size": "...",
     *                            }}
     *                   },
     *                       "..."
     *                 },
     *                 @OA\Items(
     *                     @OA\Property(
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
     *                      @OA\Property(
     *                         property="pages",
     *                         type="array",
     *                         example={{
     *                             "id": 637347,
     *                             "url": "/",
     *                             "size": 1323,
     *                         },{
     *                             "id": "...",
     *                             "url": "...",
     *                             "size": "...",
     *                         }},
     *                         @OA\Items(
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="int"
     *                              ),
     *                              @OA\Property(
     *                                  property="url",
     *                                  type="string"
     *                              ),
     *                              @OA\Property(
     *                                   property="size",
     *                                  type="int"
     *                               ),
     *                          ),
     *                     ),
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

    public function show(Site $site)
    {
        //TODO добавить связь к site
        return new SiteResource(Site::with('pages')->find($site->id));
    }

    /**
     * @OA\Patch(
     *     path="/sites/{site_id}",
     *     tags={"sites"},
     *     summary="Редактирование сайта",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="url",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 example={"url": "/newurl","name": "newSiteName"}
     *             )
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
     *                  "name": "ExampleSite",
     *                  "url": "examplesite.com",
     *                  "page_count": 99,
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

    public function update(\App\Http\Requests\SiteUpdateRequest $request, Site $site)
    {
        $site->update($request->validated());
        return new SiteResource($site);
    }

    /**
     * @OA\Delete(
     *     path="/sites/{site_id}",
     *     tags={"sites"},
     *     summary="Удаленеи страницы",
     *     @OA\Response(
     *         response=204,
     *         description="OK"
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

    public function destroy(Site $site)
    {
        $site->delete();
        return response(null, \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Post(
     *     path="/sites/scan",
     *     tags={"sites"},
     *     summary="Полное сканирование сраниц(ы)",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 example={"id": "123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 example={
     *                   "message": "Запрос на парсинг сайтов создан"
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

    public function scan(\App\Http\Requests\ScanSiteRequest $request, \App\Contracts\ScanSiteContract $action)
    {
        $id = $request->input('id', false);
        $action->scanSites(['id' => $id]);

        return response(['data' => ['message' => 'Запрос на парсинг сайтов создан']], \Illuminate\Http\Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/sites/search",
     *     tags={"sites"},
     *     summary="Поиск новых страниц",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="id",
     *                     type="int"
     *                 ),
     *                 example={"id": "123"}
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
     *                   "message": "Запрос на поиск новых страниц создан"
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

    public function search(\App\Http\Requests\ScanSiteRequest $request, \App\Actions\SearchSitePageAction $action)
    {
        $request->validated();
        $id = $request->input('id', false);
        $action->searchPages(Site::find($id));

        return response(['data' => ['message' => 'Запрос на поиск новых страниц создан']], \Illuminate\Http\Response::HTTP_OK);
    }
}
