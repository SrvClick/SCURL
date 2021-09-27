<?php
namespace Srvclick\Scurl;

class Request extends CurlRequest{

    protected string $url;
    protected string $method;
    protected array $options = [];
    protected array $configs = [];
    protected string $parameters = "";


    protected function curlRequest(): Response
    {
        //$request = new CurlRequest();
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

}
