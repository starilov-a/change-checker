<?php

namespace App\Http\Controllers;

use App\Jobs\ChangeNotificateJob;
use App\Models\Change;
use App\Models\Site;
use App\Notifications\FindChangeNotification;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TestController extends Controller
{
    public function index() {
        $notify = new FindChangeNotification(Change::changedSites());
        $notify->sendMail();
    }
}
