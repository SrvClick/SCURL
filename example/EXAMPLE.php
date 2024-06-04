<?php
/**
 * Example SCURL
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";

use Srvclick\Scurl\Scurl_Request as SCURL;

$curl = new SCURL();
$curl->setUrl('https://checkip.amazonaws.com');

$response = $curl->Send();

echo $response->getStatus()."\n";

$curl = new SCURL;
$curl->setUrl('https://odinchk.com/request.php');
$curl->setMethod("POST");
$curl->setConfigs([
    'user-agent' => "Mozilla/5.0 (compatible; SrvClick/SCURL/1.0;)",//RANDOM USER_AGENT $curl->ua(),
    'follow' => false,
    'timeout' => 60
]);
$curl->setHeaders(
    array( 'X-SRVCLICK: SRVCLICK-HEADER', )
);
$curl->setCookieName("Hey");
$curl->useCookie();

$curl->setParameters([
    'auth' => [
        'user' => "username",
        'pass' => "password",
        "otp" => 123456
    ],
    "remember" => true,
    "refer" => "refer",
    "country" => "US",
    "random" => rand(0,time()),
    "uuid" => $curl->uuid(),
]);


$response = $curl->Send();
//$response->verbose();
if ($response->getStatus() == 200){
    print_r($response->getBodyArray());
    //$curl->deleteCookie();
}elseif($response->getStatus() == 301){
    echo "REDIRECT ".$response->getRedirectUrl();
}else{
    echo "Failed ".$response->getStatus();
}
