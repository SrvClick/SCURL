<?php
namespace Srvclick\Scurl;

class Request{

    protected string $url;
    protected string $method;
    protected array $options = [];
    protected array $configs = [];
    protected string $parameters = "";
    public function setUrl($url){
        $this->url = $url;
    }
    public function setMethod($method){
        $this->method = $method;
    }
    public function setParameters($params){
         $this->parameters = is_array($params) ? http_build_query($params) : $params;
    }


    protected function curlRequest(): Response
    {
        $request = new CurlRequest();

        $request->setConfigs($this->configs);
        $request->setOptions($this->options);
        $request->setParameters($this->parameters);
        $request->setMethod($this->method);

        $request->setUrl($this->url);
        return $request->sendRequest();
    }

}
