<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Change;
use App\Http\Resources\ChangeResource;
use Illuminate\Http\Request;

class ChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $requset)
    {
        $start = $requset->input('start', 0);
        $limit = $requset->input('limit', 100);

        return ChangeResource::collection(Change::skip($start)->take($limit)->get());
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
     * @param  \App\Models\Change  $change
     * @return \Illuminate\Http\Response
     */
    public function show(Change $change)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Change  $change
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Change $change)
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
