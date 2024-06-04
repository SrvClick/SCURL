<?php
/**
 * Example SCURL REAL USAGE
 * @author SrvClick
 */
require_once __DIR__."/../vendor/autoload.php";
use Srvclick\Scurl\Scurl_Request as SCURL;
class REAL_USAGE
{
    protected SCURL $curl;
    public function __construct(){
        $this->curl = new SCURL();
        $this->curl->useCookie(true);
        $this->curl->setCookieName("login");
    }

    public function login(): void
    {
        $this->curl->setUrl('https://example.com/requests/auth/login.php');
        $this->curl->setMethod("POST");
        $this->curl->setParameters([
            'user' => 'user',
            'password' => 'password'
        ]);
        $response = $this->curl->Send();
        $response->verbose();
    }
    public function sendOTP(){
        $this->curl->setUrl('https://example.com/page/ga_confirm.php');
        $this->curl->setMethod("POST");
        $this->curl->setParameters([
            'code' => '123456',
        ]);
        $response = $this->curl->Send();
        $response->verbose();
    }
    public function index(){
        $this->curl->setUrl('https://example.com/page/home.php?user=xxx');
        $this->curl->setMethod("GET");
        $response = $this->curl->Send();
        $response->verbose();
    }

}

$real_usage = new REAL_USAGE();
$real_usage->login();
$real_usage->sendOTP();
$real_usage->index();




