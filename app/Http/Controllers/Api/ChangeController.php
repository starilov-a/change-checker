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
    public function index(\App\Http\Requests\ListRequest $request)
    {
        $request->validated();

        $start = $request->input('start', 0);
        $limit = $request->input('limit', 100);
        $id = $request->input('site_id', false);

        $changes = Change::skip($start)->take($limit);

        if ($id !== false)
            $changes = $changes->where('site_id', '=' , $id);

        return ChangeResource::collection($changes->orderBy('created_at', 'desc')->get());
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
