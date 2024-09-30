<?php
namespace Srvclick\Scurl;

use Campo\UserAgent;
use Ramsey\Uuid\Uuid;

class Scurl_Request
{
    use Curl , CurlFilesManager , Utils , Parser , Nip;
    public float $version = 1.0;
    public float $etatime;
    public function __construct()
    {
        $this->etatime = microtime(true);
    }

    protected bool $useCookie = false;
    protected string $url;
    protected array $urls;
    protected ?string $cookiename;
    protected string $method;
    protected array $options = [];
    protected array $configs = [];
    protected array $headers = [];
    protected array $parameters = [];


    protected ?array $resolveDomain = null;
    protected bool $multicurl = false;

    public static int $sock5 = CURLPROXY_SOCKS5;

    public function Send(): Response
    {
        return $this->sendRequest();
    }

    public function setResolveDomain(?array $params) : void
    {
        $this->resolveDomain = $params;
    }
    public function setMulticurl(bool $multicurl = true) : void
    {
        $this->multicurl = $multicurl;
    }

    protected function getRequest() : array
    {
        return [
            'url' => ($this->multicurl) ? $this->urls : $this->url,
            'method' => $this->method ?? "GET",
            'options' => $this->options,
            'configs' => $this->configs,
            'parameters' => $this->parameters,
            'headers' => $this->headers
        ];
    }

    public function ua($config = ['os_type' => ['Android', 'iOS','Windows'], 'device_type' => ['Mobile', 'Tablet','Desktop']]): string
    {
        return UserAgent::random($config);
    }
    public function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }
    public function setCookieName($cookiename) : void
    {
        $this->cookiename = $cookiename;
    }

    public function useCookie($bool = true) : void{
        $this->useCookie = $bool;
    }
}
