<?php

namespace Srvclick\Scurl;

class Request{

    protected string $url;
    protected string $method;
    protected Response $response;
    protected array $options = [];
    protected array $configs = [];

    public function setUrl($url){
        $this->url = $url;
    }

    public function setMethod($method){
        $this->method = $method;
    }


    protected function get(){
        $request = new CurlGet();

        $request->setConfigs($this->configs);
        $request->setOptions($this->options);

        $request->setUrl($this->url);
        $this->response = $request->sendRequest();
    }

}
