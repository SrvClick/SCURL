<?php
/**
 * Example SCURL
 *
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;

$request = new SCURL;
$request->setUrl('https://checkip.amazonaws.com');
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

$verbose = $response->verbose();


print_r($verbose);

//print_r($response);

/*
echo "Body: ".trim($response->getBody())."\n";

echo "HTTP CODE: ".$response->getStatus();
*/
