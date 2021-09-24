<?php
namespace Srvclick\Scurl;

class Verbose
{
    public function verbose(): void
    {
        $verbose = [
            'Request' => [
                'URL' => $this->url,
                'METHOD' => $this->method,
            ],
            'RESPONSE' => [

            ]
        ];

        print_r($verbose);
    }

}
