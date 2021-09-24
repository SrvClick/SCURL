<?php

namespace Srvclick\Scurl;

class CurlOptions
{
    protected array $ch_options =
        [
            'use_proxy' => true,
            'max_redirs' => 10,
            'timeout' => 30,
            'http_version' => CURL_HTTP_VERSION_1_1,
            'return_transfer' => true,
            'ssl_verifypeer' => true,
            'follow' => false,
            'encondig' => "",
            'useragent' => 'SCURL by SrvClick',
            'header' => []

        ];





    /*protected int $max_redir = 10;
    protected int $timeout = 30;
    protected int $curl_version = CURL_HTTP_VERSION_1_1;
    protected bool $return_transfer = true;
    protected bool $ssl_verifypeer = true;
    protected bool $follow = false;
    protected string $encondig = "";
    protected string $useragent = "SCURL by SrvClick";
    protected array $header = [];*/

}
