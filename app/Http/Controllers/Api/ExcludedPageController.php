<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\PageResource;
use App\Models\ExcludedPage;
use App\Models\Page;
use Illuminate\Http\Request,
    App\Http\Controllers\Controller;

class ExcludedPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\ListRequest $request)
    {
        $request->validated();

        $start = $request->input('start', 0);
        $limit = $request->input('limit', 100);

        return PageResource::collection(Page::onlyExcluded($request->site_id)->skip($start)->take($limit)->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\ExcludedPageStoreRequest $request)
    {
        $request->validated();
        $page = Page::with('site')->find($request->page_id);

        ExcludedPage::create(['site_id' => $page->site->id, 'page_id' => $page->id]);

        return response(['data' => ['message' => 'Страница добавлена в исключения']], \Illuminate\Http\Response::HTTP_OK);
    }
}
