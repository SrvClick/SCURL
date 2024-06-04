<?php
/**
 * Example SCURL DOWNLOAD FILE
 * @author SrvClick
 */

require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;
$curl = new SCURL;
$curl->setUrl('http://speedtest.ftp.otenet.gr/files/test10Mb.db');
$curl->downloadFile(__DIR__."/download/","10MB.bin");
$response = $curl->Send();

echo $response->getETA();

$response->verbose();

