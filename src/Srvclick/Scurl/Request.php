<?php
namespace Srvclick\Scurl;

class Request{

    protected string $url;
    protected string $method;
    protected array $options = [];
    protected array $configs = [];

    public function setUrl($url){
        $this->url = $url;
    }

    public function setMethod($method){
        $this->method = $method;
    }


    protected function curlRequest(): Response
    {
        $request = new CurlRequest();

        $request->setConfigs($this->configs);
        $request->setOptions($this->options);

        $request->setUrl($this->url);
        return $request->sendRequest();
    }

}
