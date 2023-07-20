<?php


namespace App\Services\Parser\Clients;

use App\Services\Parser\Response\HttpResponseInterface;


interface ClientInterface
{
    public function __construct();
    public function request($path, $method = 'GET', $opt = []): HttpResponseInterface;
}
