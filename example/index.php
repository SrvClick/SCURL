<?php
/**
 * Example SCURL
 *
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;

$request = new SCURL;
$request->setUrl('https://odinchk.com/request.php');
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
    "random" => rand(0,time()),
    "uuid" => $request->uuid(),
]);

$request->setConfigs([
    'user-agent' => $request->ua(),
    'follow' => false
]);

$response = $request->Send();

$response->verbose();
//print_r($verbose);


if ($response->getStatus() == 200){
    echo $response->getBody();
}elseif($response->getStatus() == 301){
    echo "REDIRECT ".$response->getRedirectUrl();
}else{
    echo "Failed ".$response->getStatus();
}

