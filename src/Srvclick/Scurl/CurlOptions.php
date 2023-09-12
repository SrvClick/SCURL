<?php
namespace Srvclick\Scurl;

class CurlOptions extends CurlFilesManager
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
            'user-agent' => 'SCURL by SrvClick',
            'header' => []
        ];
}
