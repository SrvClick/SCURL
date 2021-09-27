<?php
namespace Srvclick\Scurl;
use Ramsey\Uuid\Uuid;
use Campo\UserAgent;
class Request extends CurlRequest{

    protected string $url;
    protected string $method;
    protected array $options = [];
    protected array $configs = [];
    protected string $parameters = "";

    public function Send(): Response
    {

        $this->setConfigs($this->configs);
        $this->setOptions($this->options);
        $this->setParameters($this->parameters);
        $this->setMethod($this->method);
        $this->setUrl($this->url);

        return $this->sendRequest();
    }
    protected function getRequest() : array
    {
        return [
          'url' => $this->url,
          'method' => $this->method,
          'options' => $this->options,
          'configs' => $this->configs,
          'parameters' => $this->parameters,
        ];
    }

    public function ua(): string
    {
        return UserAgent::random();
    }
    public function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

}
