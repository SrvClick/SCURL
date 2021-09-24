<?php
require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;


$request = new SCURL;

$request->setUrl('https://checkip.amazonaws.com');

$request->setMethod("GET");

$request->setConfigs([
    'use_proxy' => false,
    'ssl_verifypeer' => false,
    'max_redirs' => 2,
]);





$response = $request->Send();


print_r($response);

//echo $response->getBody();


//$request->verbose();
