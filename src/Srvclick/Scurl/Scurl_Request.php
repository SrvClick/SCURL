<?php
namespace Srvclick\Scurl;

class Scurl_Request extends Request
{
    public float $version = 1.0;
    public float $etatime;
    public function __construct()
    {
        $this->etatime = microtime(true);
    }
}
