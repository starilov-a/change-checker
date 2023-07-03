<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * * @OA\Tag(
     *     name="pages",
     *     description="Страницы"
     * )
     * @OA\Get(
     *     path="/pages",
     *     tags={"pages"},
     *     summary="Список страниц",
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
     *         response=201,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                property="data",
     *                type="array",
     *                example={{
     *                  "id": 637347,
     *                  "url": "/",
     *                  "size": 1323,
     *                },{
     *                  "id": "...",
     *                  "url": "...",
     *                  "size": "...",
     *                }},
     *                @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="int"
     *                      ),
     *                      @OA\Property(
     *                         property="url",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="size",
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
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 100);
        return PageResource::collection(Page::withoutExcluded($request->site_id)->skip($start)->take($limit)->get());
    }

    public function store(Request $request)
    {
        //
    }


    public function show(Page $page)
    {
        //
    }

    /**
     * @OA\Patch(
     *     path="/pages/{page_id}",
     *     tags={"pages"},
     *     summary="Редактирование страницы",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="url",
     *                     type="string"
     *                 ),
     *                 example={"url": "/newurl"}
     *             )
     *         )
     *     ),
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

    public function update(\App\Http\Requests\PageUpdateRequest  $request, Page $page)
    {
        $page->update($request->validated());
        return response(null, \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Delete(
     *     path="/pages/{page_id}",
     *     tags={"pages"},
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

    public function destroy(Page $page)
    {
        $page->delete();
        return response(null, \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }
}
