<?php
/**
 * Example SCURL PROXY USAGE
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;

$proxy = [
    'proxy' => 'PROXY IP',
    'proxy_port' => 'PROXY PORT',
    'proxy_user' => 'PROXY USERNAME',
    'proxy_pass' => 'PROXY PASSWORD'
];
$curl = new SCURL;
$curl->setUrl('https://checkip.amazonaws.com/');

$curl->setProxy($proxy);
$curl->setConfigs(
    ['follow' => true]
);
$response = $curl->Send();

$response->verbose();

echo $response->getBody();

