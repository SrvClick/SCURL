<?php
/**
 * Example SCURL HTTP_AUTH
 * @author SrvClick
 */
require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;
$curl = new SCURL();
$curl->setUrl('https://example.com');
$curl->setConfigs([
    'http_auth' => true,
    'http_user' => 'admin',
    'http_pass' => 'admin'
]);
$response = $curl->Send();
$response->verbose();
