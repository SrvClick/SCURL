<?php
/**
 * Example SCURL HTTP_AUTH
 * @author SrvClick
 */
require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request;
$curl = new Scurl_Request();
echo $curl->ParseCurl($_POST['code']);