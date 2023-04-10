<?php

namespace App\Http\Controllers\Api;

use App\Contracts\AddSiteContract;
use App\Http\Resources\SiteResource;
use App\Http\Controllers\Controller;
use App\Models\Site;
use \App\Services\ParserService;
use Facade\FlareClient\Http\Response;
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

        return SiteResource::collection(Site::skip($start)->take($limit)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\SiteStoreRequest $request, AddSiteContract $addSiteAction)
    {
        $urls = $request->validated()['urls'];
        $addSiteAction->addSites($urls);
        return response('Запрос на парсинг сайтов создан', \Illuminate\Http\Response::HTTP_OK);
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
}
