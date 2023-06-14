<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AddSiteContract;
use App\Http\Resources\SiteResource;
use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 20);

        return SiteResource::collection(Site::skip($start)->take($limit)->orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\SiteStoreRequest $request, AddSiteContract $action)
    {
        $urls = $request->validated()['urls'];
        $action->addSites($urls);
        return response(['data' => ['message' => 'Запрос на добавление и парсинг сайтов создан']], \Illuminate\Http\Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        //TODO добавить связь к site
        return new SiteResource(Site::with('pages')->find($site->id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(\App\Http\Requests\SiteUpdateRequest $request, Site $site)
    {
        $site->update($request->validated());
        return new SiteResource($site);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Site $site)
    {
        $site->delete();
        return response(null, \Illuminate\Http\Response::HTTP_NO_CONTENT);
    }

    public function scan(\App\Http\Requests\ScanSiteRequest $request, \App\Contracts\ScanSiteContract $action)
    {
        $id = $request->input('id', false);
        $action->scanSites(['id' => $id]);

        return response(['data' => ['message' => 'Запрос на парсинг сайтов создан']], \Illuminate\Http\Response::HTTP_OK);
    }

    public function search(\App\Http\Requests\ScanSiteRequest $request, \App\Actions\SearchSitePageAction $action)
    {
        $request->validated();
        $id = $request->input('id', false);
        $action->searchPages(Site::find($id));

        return response(['data' => ['message' => 'Запрос на поиск новых страниц создан']], \Illuminate\Http\Response::HTTP_OK);
    }
}
