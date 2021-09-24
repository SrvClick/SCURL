<?php

namespace Srvclick\Scurl;

class Scurl_Request extends Request
{
    protected string $version = '1';
    protected string $url;
    protected string $method = "GET";
    protected array $options = [];
    protected array $configs = [];

    public function setOptions(array $options){
        $this->options = $options;
    }

    public function setConfigs(array $configs){
        $this->configs = $configs;
    }


    public function Send(): Response
    {
        return $this->curlRequest();
    }





}
