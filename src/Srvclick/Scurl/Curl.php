<?php
namespace Srvclick\Scurl;
use Exception;
trait Curl
{


    protected array $ch_options =
        [
            'use_proxy' => true,
            'max_redirs' => 10,
            'timeout' => 30,
            'http_version' => CURL_HTTP_VERSION_1_1,
            'return_transfer' => true,
            'ssl_verifypeer' => true,
            'follow' => false,
            'encondig' => "",
            'user-agent' => 'SCURL by SrvClick',
            'header' => []
        ];
    protected array $proxy = [];
    protected bool $interceptCookies = false;
    protected bool $download = false;

    protected array $arrayParameters = [];
    protected bool $isArrayParams = false;
    protected array $downloadPath = [];
    protected array $downloadName = [];


    public function downloadFile($path=null, $name=null): bool
    {
        if (empty($path) || empty($name)){
            return false;
        }


        $this->download = true;
        $this->downloadPath[] = $path;
        $this->downloadName[] = $name;
        return true;
    }
    public function setInterceptCookie($bool) : void{
        $this->interceptCookies = $bool;
    }
    public function setUrl($url) : bool
    {
        if (is_array($url)) { $this->urls = $url; return true; }
        $this->url = $url;
        return true;
    }
    public function MultiUrl($url) : bool
    {
        $this->urls[] = $url;
        $this->multicurl = true;
        return true;
    }
    public function setOptions(array $options) : void
    {
        $this->options = $options;
    }
    public function setConfigs(array $configs) : void
    {
        foreach ($configs as $name => $value){
            $this->ch_options[strtolower($name)] = $value;
        }
        $this->configs = $configs;
    }


    public function setProxy(array $proxy) : void
    {
        foreach ($proxy as $name => $value){
            $this->ch_options[strtolower($name)] = $value;
        }
        $this->proxy = $proxy;
    }


    public function setHeaders($headers) : void
    {
        $this->headers = $headers;
    }
    public function getParameters($i = 0) : string{
        return $this->parameters[$i];
    }

    public function getHeaders() : array{
        return $this->headers;
    }

    public function setParameters($params) : void
    {
        $this->parameters[] = is_array($params) ? http_build_query($params) : $params;
    }

    public function setArrayParameters($params) : void
    {
        $this->arrayParameters = $params;
        $this->isArrayParams = true;
    }
    public function setMethod(string $method) : void
    {
        $this->method = $method;
    }
    public function sendRequest(): Response
    {
        $multiCurl = [];
        $options = [
            CURLOPT_TIMEOUT => $this->ch_options['timeout'],
            CURLOPT_RETURNTRANSFER => $this->ch_options['return_transfer'],
            CURLOPT_SSL_VERIFYPEER => ($this->ch_options['ssl_verify_peer'] ?? $this->ch_options['ssl_verifypeer']) ,
            CURLOPT_ENCODING => $this->ch_options['encondig'],
            CURLOPT_HTTP_VERSION => $this->ch_options['http_version'],
            CURLOPT_USERAGENT => $this->ch_options['user-agent'],
            CURLOPT_FOLLOWLOCATION => $this->ch_options['follow'],
            CURLOPT_MAXREDIRS => $this->ch_options['max_redirs'],
        ];
        foreach ($this->options as $name => $value){
            $options[$name] = $value;
        }
        if (isset($this->ch_options['proxy']) && isset($this->ch_options['proxy_port']) && $this->ch_options['use_proxy']){
            $options[CURLOPT_PROXY] = $this->ch_options['proxy'];
            $options[CURLOPT_PROXYPORT] = $this->ch_options['proxy_port'];
                if (isset($this->ch_options['proxy_user']) && isset($this->ch_options['proxy_pass'])){
                    $options[CURLOPT_PROXYUSERPWD] = $this->ch_options['proxy_user'].":".$this->ch_options['proxy_pass'];
                }
        }
        foreach ($this->headers as $header) {
            if (preg_match('/^[Uu]ser-[Aa]gent\s*:\s*(.*)$/i', $header, $matches)) {
                $options[CURLOPT_USERAGENT] = trim($matches[1]);
                break;
            }
        }


        if ($this->useCookie && !empty($this->cookiename)){
            if (!is_dir(__DIR__."/cookies")){
                mkdir(__DIR__."/cookies","0777");
            }
            $options[CURLOPT_COOKIEJAR] = __DIR__."/cookies/".$this->cookiename;
            $options[CURLOPT_COOKIEFILE] = __DIR__."/cookies/".$this->cookiename;

        }

        $options[CURLOPT_HEADER] = (int)$this->interceptCookies;

        if (isset($this->ch_options['http_auth']) && isset($this->ch_options['http_user']) && isset($this->ch_options['http_pass'])){
            $options[CURLOPT_USERPWD] = $this->ch_options['http_user'].":".$this->ch_options['http_pass'];
        }

        if (isset($this->method) && $this->method == "POST" && !$this->multicurl){
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = ($this->isArrayParams) ? $this->arrayParameters : $this->parameters[0];
        }
        if (isset($this->ch_options['custom_method']) && !empty($this->ch_options['custom_method'])){
            $options[CURLOPT_CUSTOMREQUEST] = $this->ch_options['custom_method'];
        }

        if (!$this->multicurl){
            $options[ CURLOPT_URL ]  = $this->url ;
            $ch = curl_init();
            curl_setopt_array($ch, $options);
        }else{
            $mh = curl_multi_init();

            foreach ($this->urls as $i => $url){
                $multiCurl[$i] = curl_init($url);
                curl_setopt_array($multiCurl[$i], $options);

                if (isset($this->method) && $this->method == "POST"){
                    $options[CURLOPT_POST] = true;
                    curl_setopt($multiCurl[$i], CURLOPT_POSTFIELDS , $this->parameters[$i]);
                }
                curl_multi_add_handle($mh, $multiCurl[$i]);
            }
            $running = 0;
            do {
                curl_multi_exec($mh, $running);
                curl_multi_select($mh);
            } while ($running > 0);

            $responses = [];
            $response = new Response;

            foreach ($multiCurl as $i => $ch) {
                $start = microtime(true);
                $responses[$i] = curl_multi_getcontent($ch);
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
                if($responses[$i] === false) {
                    $response->setError( curl_error($ch) );
                }
                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $response->setStatus($statusCode);

                if ($statusCode >= 300 && $statusCode <= 399) {
                    $response->setRedirectUrl(curl_getinfo($ch, CURLINFO_REDIRECT_URL));
                }
                $response->setProxy($this->proxy);
                $response->setRequest($this->getRequest());
                $response->setBody($responses[$i]);
                $response->setETA( round(microtime(true) - $start , 3) );
                if ($this->download){
                    if ($statusCode != 200) {
                        $response->setError("No se encontro el fichero.");
                    }
                    if (!is_dir($this->downloadPath[$i])){ mkdir($this->downloadPath[$i],"0777"); }

                    if (file_put_contents($this->downloadPath[$i]."/".$this->downloadName[$i], $responses[$i], FILE_APPEND) === false) {
                        $response->setError( "No se logro guardar el archivo");
                    }
                }

            }

            curl_multi_close($mh);

            return $response;
        }

        $cr_response = curl_exec($ch);
        $cr_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = new Response;
        if ($this->download){
            if ($cr_status != 200) {  $response->setError("No se encontro el fichero."); return $response; }


            if (!is_dir($this->downloadPath[0])){ mkdir($this->downloadPath[0],"0777"); }
            if (file_put_contents($this->downloadPath[0]."/".$this->downloadName[0], $cr_response, FILE_APPEND) === false) {
                $response->setError( "No se logro guardar el archivo");
            }
        }
        if ($cr_response === false){
            $response->setError(curl_error($ch));
        }
        if ($this->useCookie) {
            $response->setCookie([
                "useCookie" => $this->useCookie,
                "cookieName" => $this->cookiename,
                "cookiePath" => __DIR__ . "/cookies/" . $this->cookiename
            ]);
        }

        if ($cr_response) {
            if ($this->interceptCookies) {
                preg_match_all('/Set-Cookie:(?<cookie>\s*.*)$/im', $cr_response, $cookies);
                $response->setResponseCookies($cookies[0]);

                $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $response->setHeader(substr($cr_response, 0, $header_size));
                $cr_response = substr($cr_response, $header_size);


            }
        }

        $response->setProxy($this->proxy);
        $response->setRequest($this->getRequest());
        $response->setBody($cr_response);


        $response->setStatus($cr_status);
        if ($cr_status >= 300 && $cr_status <= 399) {
            $response->setRedirectUrl(curl_getinfo($ch, CURLINFO_REDIRECT_URL));
        }

        $response->setETA(microtime(true) - $this->etatime);

        return $response;
    }


}
