<?php

namespace App\Notifications;

use App\Services\MailService;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FindChangeNotification extends Notification
{
    use Queueable;

    protected $sites;
    protected $address = [
        'anton_starilov@mail.ru',
        'antonstarilov@yandex.ru'
    ];

    public function __construct($sites) {
        $this->sites = $sites;
    }

    public function sendMail() {
        (new MailService)->sendChangeNotificate($this->address, $this->sites);
    }
}
