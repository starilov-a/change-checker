<?php

namespace App\Services\GuzzleMonitor;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\MessageTrait;
use Illuminate\Support\Facades\Log;


class MonitorClient extends Client {
    use MonitorMessageTrait, MessageTrait {
        MonitorMessageTrait::getBody insteadof MessageTrait;
    }

    public function __construct(array $config = [])
    {
        $config['handler'] = HandlerStack::create();
        return parent::__construct($config);
    }
}
