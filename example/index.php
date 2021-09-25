<?php
/**
 * Example SCURL
 *
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;

$request = new SCURL;
$request->setUrl('https://webhook.site/33aaf937-d806-46d1-ab78-6bc53023d94c?a=1');
$request->setMethod("POST");

$request->setParameters([
    'auth' => [
        'user' => "username",
        'pass' => "password",
        "otp" => 123456
    ],
    "remember" => true,
    "refer" => "refer",
    "country" => "US",
    "random" => rand(0,time())
]);

$request->setConfigs([
    'follow' => true
]);

//setOptions remplazara cualquier valor de setConfigs.

$request->setOptions([
    CURLOPT_FOLLOWLOCATION => false,
]);

/*
 * Follow = false
 */


$response = $request->Send();

//print_r($response);


echo "Body: ".trim($response->getBody())."\n";

echo "HTTP CODE: ".$response->getStatus();
