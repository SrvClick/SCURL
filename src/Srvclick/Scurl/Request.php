<?php
namespace Srvclick\Scurl;
use Ramsey\Uuid\Uuid;
use Campo\UserAgent;
class Request extends CurlRequest{
    protected bool $useCookie = false;
    protected string $url;
    protected string $cookiename;
    protected string $method;
    protected array $options = [];
    protected array $configs = [];
    protected array $headers = [];
    protected string $parameters = "";

    public function Send(): Response
    {
        return $this->sendRequest();
    }
    protected function getRequest() : array
    {
        return [
          'url' => $this->url,
          'method' => $this->method ?? "GET",
          'options' => $this->options,
          'configs' => $this->configs,
          'parameters' => $this->parameters,
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
