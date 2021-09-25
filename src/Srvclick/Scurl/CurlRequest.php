<?php
namespace Srvclick\Scurl;

class CurlRequest extends CurlOptions
{

    protected string $url;
    protected array $options = [];
    protected array $configs = [];
    protected string $parameters = "";
    protected string $method = "";

    public function setUrl($url) : void
    {
        $this->url = $url;
    }
    public function setOptions(array $options) : void
    {
        $this->options = $options;
    }
    public function setConfigs(array $configs) : void
    {
        foreach ($configs as $name => $value){
            $this->ch_options[$name] = $value;
        }
    }
    public function setParameters($params) : void
    {
        $this->parameters = is_array($params) ? http_build_query($params) : $params;
    }
    public function setMethod(string $method) : void
    {
        $this->method = $method;
    }

    public function sendRequest(): Response
    {
        $ch = curl_init();
        $options = [
            CURLOPT_URL => $this->url,
            CURLOPT_TIMEOUT => $this->ch_options['timeout'],
            CURLOPT_RETURNTRANSFER => $this->ch_options['return_transfer'],
            CURLOPT_SSL_VERIFYPEER => $this->ch_options['ssl_verifypeer'],
            CURLOPT_ENCODING => $this->ch_options['encondig'],
            CURLOPT_HTTP_VERSION => $this->ch_options['http_version'],
            CURLOPT_HTTPHEADER => $this->ch_options['header'],
            CURLOPT_USERAGENT => $this->ch_options['useragent'],
            CURLOPT_FOLLOWLOCATION => $this->ch_options['timeout'],
            CURLOPT_MAXREDIRS => $this->ch_options['max_redirs'],
        ];
        foreach ($this->options as $name => $value){
            $options[$name] = $value;
        }
        if (isset($this->ch_options['proxy']) && isset($this->ch_options['proxy_endpoint']) && isset($this->ch_options['proxy_port']) && $this->ch_options['use_proxy']){
            $options[CURLOPT_PROXY] = $this->ch_options['proxy_endpoint'];
            $options[CURLOPT_PROXYPORT] = $this->ch_options['proxy_port'];
                if (isset($this->ch_options['proxy_user']) && isset($this->ch_options['proxy_pass'])){
                    $options[CURLOPT_PROXYUSERPWD] = $this->ch_options['proxy_user'].":".$this->ch_options['proxy_pass'];
                }
        }
        if (isset($this->ch_options['http_auth']) && isset($this->ch_options['http_user']) && isset($this->ch_options['http_pass'])){
            $options[CURLOPT_USERPWD] = $this->ch_options['http_user'].":".$this->ch_options['http_pass'];
        }
        if ($this->method == "POST"){
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $this->parameters;
        }
        if (isset($this->ch_options['custom_method']) && !empty($this->ch_options['custom_method'])){
            $options[CURLOPT_CUSTOMREQUEST] = $this->ch_options['custom_method'];
        }



        curl_setopt_array($ch, $options);
        $cr_response = curl_exec($ch);
        $cr_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = new Response;

        if ($cr_response === false){
            $response->setError(curl_error($ch));
        }



        $response->setBody($cr_response);
        $response->setStatus($cr_status);
        return $response;
    }


}
