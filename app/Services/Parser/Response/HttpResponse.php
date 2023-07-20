<?php


namespace App\Services\Parser\Response;


class HttpResponse implements HttpResponseInterface
{

    protected $body;
    protected $code;

    public function __construct($body, $code)
    {
        $this->body = $body;
        $this->code = $code;
    }

    public function getStatusCode()
    {
        return $this->code;
    }

    public function getBody()
    {
        return $this->body;
    }
}
