<?php

namespace App\Http\Controllers\Web;

use App\Contracts\AddSiteContract;
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
    public function index()
    {
        $sites = Site::orderBy('created_at', 'desc')->paginate(20);
        $countSites = Site::all()->count();

        return view('sites.list', compact('sites', 'countSites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\App\Http\Requests\SiteStoreRequest $request, AddSiteContract $action)
    {
        $urls = explode(',', $request->validated()['urls']);

        $action->addSites($urls);

        return redirect('/sites');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function scan(\App\Http\Requests\ScanSiteRequest $request, \App\Contracts\ScanSiteContract $action)
    {
        $id = $request->input('id', false);
        $action->scanSites(['id' => $id]);

        return redirect('/sites');
    }

    public function searchpage(\App\Http\Requests\ScanSiteRequest $request, \App\Actions\SearchSitePageAction $action)
    {
        $id = $request->input('id', false);
        $action->searchPages(Site::find($id), true);

        return redirect('/sites');
    }

    public function searchsite(\App\Http\Requests\SiteSearchRequest $request) {
        $url = $request->input('field', false);

        $sites = Site::where('url', 'LIKE', '%'.$url.'%')->paginate(20);
        $countSites = Site::where('url', 'LIKE', '%'.$url.'%')->count();
        return view('sites.list', compact('sites', 'countSites'));
    }

    public function checkstatus(Site $site, \App\Actions\StatusSiteAction $action) {
        $action->checkSiteStatus($site);
        return redirect('/sites');
    }
}
