<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\ChangeCollection;
use App\Http\Resources\ChangeSiteResource;
use App\Models\Change;
use App\Http\Resources\ChangeResource;
use App\Models\Site;
use Illuminate\Http\Request;

class ChangeController extends Controller
{
    /**
     * * @OA\Tag(
     *     name="changes",
     *     description="Зафиксированные изменения"
     * )
     * @OA\Get(
     *     path="/changes",
     *     tags={"changes"},
     *     summary="Список изменений",
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
        $id = $request->input('site_id', false);

        if ($id !== false){
            return (new ChangeSiteResource(Site::with(['changes' => function ($q) use ($start, $limit) {
                $q->skip($start)->take($limit);
            }])->find($id)))->additional(['meta' => ['start' => $start, 'limit' => $limit]]);
        }

        return (new ChangeCollection(Change::skip($start)->take($limit)->orderBy('created_at', 'desc')->get()))->additional(['meta' => ['start' => $start, 'limit' => $limit]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Change  $change
     * @return \Illuminate\Http\Response
     */
    public function show(Change $change)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Change  $change
     * @return \Illuminate\Http\Response
     */
    public function destroy(Change $change)
    {
        //
    }
}
