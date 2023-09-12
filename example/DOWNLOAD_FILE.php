<?php
/**
 * Example SCURL DOWNLOAD FILE
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;
$curl = new SCURL;
$curl->setUrl('https://get.cdnpkg.com/1mb/0.0.1/1mb');
$curl->downloadFile(__DIR__,"1mb.txt");
$response = $curl->Send();

