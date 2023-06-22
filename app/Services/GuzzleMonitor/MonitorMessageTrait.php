<?php


namespace App\Services\GuzzleMonitor;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

trait MonitorMessageTrait
{
    /** @var StreamInterface|null */
    private $stream;

    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = Utils::streamFor('');
        }

        return mb_convert_encoding($this->stream, "UTF8");
    }
}
