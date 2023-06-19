<?php

namespace App\Http\Controllers\Web;

use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\Request,
    App\Http\Controllers\Controller;

class HistoryChangeController extends Controller
{

    public function sites()
    {
        $sites = Site::has('pages')->has('historyChanges')->paginate(20);
        $countSites = Site::has('pages')->has('historyChanges')->get()->count();
        return view('historyChanges.sites', compact('sites', 'countSites'));
    }

    public function pages(Site $site)
    {
        $pages = $site->pages()->has('historyChanges')->paginate(20);
        $countPages = $site->pages()->has('historyChanges')->get()->count();
        return view('historyChanges.pages', compact('pages', 'countPages'));
    }

    public function changes(Page $page)
    {
        $changes = $page->historyChanges()->paginate(20);
        $countChanges = $page->historyChanges()->get()->count();
        return view('historyChanges.changes', compact('changes', 'countChanges'));
    }
}
