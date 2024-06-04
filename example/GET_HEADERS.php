<?php
/**
 * Example SCURL GET GEADERS
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;
$curl = new SCURL;
$curl->setUrl('https://www.google.com');

$curl->setInterceptCookie(true);
$response = $curl->Send();
print_r($response->getBody());
print_r($response->getResponseCoookies());


