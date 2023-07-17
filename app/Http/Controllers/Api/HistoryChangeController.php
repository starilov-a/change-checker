<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class HistoryChangeController extends Controller
{
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
}
