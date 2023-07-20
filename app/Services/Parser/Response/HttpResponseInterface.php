<?php


namespace App\Services\Parser\Response;


interface HttpResponseInterface
{
    public function __construct($body, $code);
    public function getStatusCode();
    public function getBody();
}
