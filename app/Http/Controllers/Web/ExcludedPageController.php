<?php

namespace App\Http\Controllers\Web;

use App\Http\Resources\PageResource;
use App\Models\Change;
use App\Models\ExcludedPage;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\Request,
    App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ExcludedPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(\App\Http\Requests\ListRequest $request)
    {
        $sites = Site::has('excludedPages')->paginate(20);
        $countSites = Site::has('excludedPages')->get()->count();
        return view('excludedPages.sites', compact('sites', 'countSites'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        $excludedPages = Page::onlyExcluded($site->id)->orderBy('created_at', 'desc')->paginate(20);
        $countExcludedPage = Page::onlyExcluded($site->id)->count();
        return view('excludedPages.list', compact('excludedPages', 'countExcludedPage'));
    }
}
