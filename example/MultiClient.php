<?php
require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;
$start = microtime(true);
$curl = new SCURL();
$curl->setMulticurl();
for ($i = 0; $i < 10; $i++) {
    $curl->MultiUrl('https://jsonplaceholder.typicode.com/todos/'.$i);
    $curl->downloadFile(__DIR__."/downloads/","item_".$i.".json");
}
$response = $curl->Send();
for ($i = 0; $i < $response->getCount(); $i++) {
    echo "Peticion ".$i." Con HTTP STATUS ".$response->getStatus($i)."\n";
}