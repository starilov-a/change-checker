<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Change;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = Site::has('changes')->paginate(20);
        $countSites = Site::has('changes')->get()->count();
        return view('changes.sites', compact('sites', 'countSites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Site $site)
    {
        $changes = Change::with('sites')->where('site_id',$site->id)->orderBy('created_at', 'desc')->paginate(20);
        $countChanges = DB::table('changes')->where('site_id',$site->id)->count();
        return view('changes.list', compact('changes', 'countChanges'));
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

    public function searchchangesite(\App\Http\Requests\SiteSearchRequest $request) {
        $url = $request->input('field', false);

        $sites = Site::has('changes')->where('url', 'LIKE', '%'.$url.'%')->paginate(20);
        $countSites = Site::has('changes')->where('url', 'LIKE', '%'.$url.'%')->count();
        return view('changes.sites', compact('sites', 'countSites'));
    }
}
