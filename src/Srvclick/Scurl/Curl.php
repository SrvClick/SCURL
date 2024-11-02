<?php
namespace Srvclick\Scurl;

trait Curl
{
    protected array $ch_options = [
        'use_proxy' => true,
        'max_redirs' => 10,
        'timeout' => 30,
        'http_version' => CURL_HTTP_VERSION_1_1,
        'return_transfer' => true,
        'ssl_verifypeer' => true,
        'ssl_verify_host' => false,
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
    protected array $cookieExtra = [];
    protected array $responsedCookiesRaw = [];

    protected ?string $curlDump = null;
    public function reset(): void
    {
        $this->parameters = [];

    }
    public function getDump()
    {
        return $this->curlDump;
    }

    public function addCookieExtra(array $cookie) : void
    {
        $this->cookieExtra = $cookie;

    }
    /**
     * Configures download path and name.
     *
     * @param string|null $path
     * @param string|null $name
     * @return bool
     */
    public function downloadFile(?string $path = null, ?string $name = null): bool
    {
        if (empty($path) || empty($name)) {
            return false;
        }
        $this->download = true;
        $this->downloadPath[] = $path;
        $this->downloadName[] = $name;
        return true;
    }

    /**
     * Sets whether to intercept cookies.
     *
     * @param bool $bool
     */
    public function setInterceptCookie(bool $bool): void
    {
        $this->interceptCookies = $bool;
    }

    /**
     * Sets the URL for the request.
     *
     * @param array|string $url
     * @return bool
     */
    public function setUrl(array|string $url): bool
    {
        if (is_array($url)) {
            $this->urls = $url;
            return true;
        }
        $this->url = $url;
        return true;
    }

    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Adds a URL for multi-curl requests.
     *
     * @param string $url
     * @return bool
     */
    public function MultiUrl(string $url): bool
    {
        $this->urls[] = $url;
        $this->multicurl = true;
        return true;
    }

    /**
     * Sets cURL options.
     *
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    /**
     * Sets cURL configurations.
     *
     * @param array $configs
     */
    public function setConfigs(array $configs): void
    {
        foreach ($configs as $name => $value) {
            $this->ch_options[strtolower($name)] = $value;
        }
        $this->configs = $configs;
    }

    /**
     * Sets proxy configurations.
     *
     * @param array $proxy
     */
    public function setProxy(array $proxy): void
    {
        foreach ($proxy as $name => $value) {
            $this->ch_options[strtolower($name)] = $value;
        }
        $this->proxy = $proxy;
    }

    /**
     * Sets request headers.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Gets request parameters.
     *
     * @param int $i
     * @return string
     */
    public function getParameters(int $i = 0): string
    {
        return $this->parameters[$i];
    }

    /**
     * Gets request headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Sets request parameters.
     *
     * @param array|string $params
     */
    public function setParameters(array|string $params): void
    {
        $this->parameters[] = is_array($params) ? http_build_query($params) : $params;
    }



    /**
     * Sets request parameters as an array.
     *
     * @param array $params
     */
    public function setArrayParameters(array $params): void
    {
        $this->arrayParameters = $params;
        $this->isArrayParams = true;
    }

    /**
     * Sets HTTP method for the request.
     *
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function formatHeaders($headers) {
        $formattedHeaders = [];

        foreach ($headers as $key => $value) {
            // Verifica si el arreglo es asociativo (clave => valor)
            if (is_string($key)) {
                // Convierte la clave y el valor al formato "Nombre: Valor"
                $formattedHeaders[] = "$key: $value";
            } else {
                // Si ya está en el formato correcto, solo agrégalo
                $formattedHeaders[] = $value;
            }
        }

        return $formattedHeaders;
    }

    /**
     * Sends the HTTP request and returns a Response object.
     *
     * @return Response
     */
    public function sendRequest(): Response
    {
        $options = $this->prepareCurlOptions();



        if ($this->multicurl) {
            return $this->executeMultiCurl($options);
        } else {
            return $this->executeSingleCurl($options);
        }
    }

    public function getResolves(): ?array
    {
        if ($this->resolveDomain) {
            $host = $this->resolveDomain['host'];
            $port = $this->resolveDomain['port'];
            $ip = $this->resolveDomain['ip'];
            return ["$host:$port:$ip"];
        }
        return null;
    }


    /**
     * Prepares cURL options based on configured settings.
     *
     * @return array
     */
    protected function prepareCurlOptions(): array
    {

        $resolves = $this->getResolves();
        $options = [
            CURLOPT_TIMEOUT => $this->ch_options['timeout'],
            CURLOPT_RETURNTRANSFER => $this->ch_options['return_transfer'],
            CURLOPT_SSL_VERIFYPEER => ($this->ch_options['ssl_verify_peer'] ?? $this->ch_options['ssl_verifypeer']),
            CURLOPT_SSL_VERIFYHOST => ($this->ch_options['ssl_verify_host'] ?? $this->ch_options['ssl_verify_host']),
            CURLOPT_ENCODING => $this->ch_options['encondig'],
            CURLOPT_HTTP_VERSION => $this->ch_options['http_version'],
            CURLOPT_USERAGENT => $this->ch_options['user-agent'],
            CURLOPT_FOLLOWLOCATION => $this->ch_options['follow'],
            CURLOPT_MAXREDIRS => $this->ch_options['max_redirs'],
            CURLOPT_RESOLVE => $resolves ?: [],

        ];


        foreach ($this->options as $name => $value) {
            $options[$name] = $value;
        }

        if (isset($this->ch_options['proxy']) && isset($this->ch_options['proxy_port']) && $this->ch_options['use_proxy']) {
            $options[CURLOPT_PROXY] = $this->ch_options['proxy'];
            $options[CURLOPT_PROXYPORT] = $this->ch_options['proxy_port'];
            if (isset($this->ch_options['proxy_user']) && isset($this->ch_options['proxy_pass'])) {
                $options[CURLOPT_PROXYUSERPWD] = $this->ch_options['proxy_user'] . ":" . $this->ch_options['proxy_pass'];
            }
        }


        foreach ($this->headers as $header) {
            if (preg_match('/^[Uu]ser-[Aa]gent\s*:\s*(.*)$/i', $header, $matches)) {
                $options[CURLOPT_USERAGENT] = trim($matches[1]);
                break;
            }
        }

        if (!empty($this->headers)){

            $options[CURLOPT_HTTPHEADER] = $this->formatHeaders($this->getHeaders());
        }

        if ($this->useCookie && !empty($this->cookiename)) {

            if (!is_dir(__DIR__ . "/cookies")) {
                mkdir(__DIR__ . "/cookies", 0777);
            }
            $options[CURLOPT_COOKIEJAR] = __DIR__ . "/cookies/" . $this->cookiename;
            $options[CURLOPT_COOKIEFILE] = __DIR__ . "/cookies/" . $this->cookiename;
        }
        if (count($this->cookieExtra) > 0){
            $extra_cookies = '';
            foreach ($this->cookieExtra as $key => $value) {
                $extra_cookies .= $key . '=' . urlencode($value) . '; ';
            }
            $extra_cookies = rtrim($extra_cookies, '; ');

            $options[CURLOPT_COOKIE] = (String)$extra_cookies;


        }
        $options[CURLOPT_HEADER] = (int)$this->interceptCookies;

        if (isset($this->ch_options['http_auth']) && isset($this->ch_options['http_user']) && isset($this->ch_options['http_pass'])) {
            $options[CURLOPT_USERPWD] = $this->ch_options['http_user'] . ":" . $this->ch_options['http_pass'];
        }

        if (isset($this->method) && $this->method == "POST" && !$this->multicurl) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = ($this->isArrayParams) ? $this->arrayParameters : $this->parameters[0];
        }

        if (isset($this->ch_options['custom_method']) && !empty($this->ch_options['custom_method'])) {
            $options[CURLOPT_CUSTOMREQUEST] = $this->ch_options['custom_method'];
        }

        return $options;
    }

    /**
     * Executes a single cURL request.
     *
     * @param array $options
     * @return Response
     */
    protected function executeSingleCurl(array $options): Response
    {
        $options[CURLOPT_URL] = $this->url;
        $ch = curl_init();
        curl_setopt_array($ch, $options);

        $response = $this->processCurlResponse( $ch);

        //$this->curlDump = $this->dump_curl_request($ch);
        curl_close($ch);

        return $response;
    }



    public function dump_curl_request($ch) {
        // Obtener toda la información del manejador cURL
        $info = curl_getinfo($ch);

        // Comenzar a construir el comando curl
        $command = "curl";

        // Manejar la URL
        if (!empty($info['url'])) {
            $command .= " '" . $info['url'] . "'";
        }

        // Manejar encabezados
        $headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        if (!empty($headers)) {
            foreach (explode("\n", $headers) as $header) {
                if (trim($header)) {
                    $command .= " -H '" . trim($header) . "'";
                }
            }
        }

        // Manejar cookies
        $cookieFile = tempnam(sys_get_temp_dir(), 'cookie'); // Obtener archivo temporal para cookies
        if ($cookieFile) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
            $command .= " --cookie '" . $cookieFile . "'";
        }

        // Manejar el método POST y datos enviados
        if (isset($info['request_method']) && $info['request_method'] == 'POST') {
            $command .= " -X POST";

            // Obtener datos POST si los hay
            $postfields = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
            if (!empty($postfields)) {
                $command .= " -d '" . json_encode($postfields) . "'";
            }
        }

        // Manejar uso de proxy
        if (isset($curl_options[CURLOPT_PROXY])) {
            $command .= " --proxy '" . $curl_options[CURLOPT_PROXY] . "'";
        }

        // Manejar autenticación
        if (isset($curl_options[CURLOPT_USERPWD])) {
            $command .= " --user '" . $curl_options[CURLOPT_USERPWD] . "'";
        }
        // Manejar timeout
        if (isset($curl_options[CURLOPT_TIMEOUT])) {
            $command .= " --max-time '" . $curl_options[CURLOPT_TIMEOUT] . "'";
        }


        // Manejar configuración de SSL
        if (isset($curl_options[CURLOPT_SSL_VERIFYPEER]) && !$curl_options[CURLOPT_SSL_VERIFYPEER]) {
            $command .= " --insecure";
        }


        // Otros métodos HTTP (por ejemplo, PUT, DELETE)
        if (isset($curl_options[CURLOPT_CUSTOMREQUEST])) {
            $command .= " -X '" . $curl_options[CURLOPT_CUSTOMREQUEST] . "'";
        }


        // Devolver el comando completo
        return $command;
    }
    /**
     * Executes multiple cURL requests concurrently.
     *
     * @param array $options
     * @return Response
     */
    protected function executeMultiCurl(array $options): Response
    {
        $mh = curl_multi_init();
        $multiCurl = [];

        foreach ($this->urls as $i => $url) {
            $multiCurl[$i] = curl_init($url);
            curl_setopt_array($multiCurl[$i], $options);
            if (isset($this->method) && $this->method == "POST") {
                curl_setopt($multiCurl[$i], CURLOPT_POSTFIELDS, $this->parameters[$i]);
            }
            curl_multi_add_handle($mh, $multiCurl[$i]);
        }

        $running = 0;
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        $response = new Response;

        foreach ($multiCurl as $i => $ch) {
            $responses[$i] = curl_multi_getcontent($ch);
            $this->handleMultiCurlResponse($response, $ch, $responses[$i], $i);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }

        curl_multi_close($mh);
        return $response;
    }

    /**
     * Processes the response from a single cURL request.
     *
     * @param resource $ch
     * @return Response
     */
    protected function processCurlResponse($ch): Response
    {
        $responseContent = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $response = new Response;
        if ($this->download) {
            if ($statusCode != 200) {
                $response->setError("No se encontró el fichero.");
                return $response;
            }
            if (!is_dir($this->downloadPath[0])) {
                mkdir($this->downloadPath[0], 0777);
            }
            if (file_put_contents($this->downloadPath[0] . "/" . $this->downloadName[0], $responseContent, FILE_APPEND) === false) {
                $response->setError("No se logró guardar el archivo");
            }
        }

        if ($responseContent === false) {
            $response->setError(curl_error($ch));
        }


        if (!is_null($this->curlDump)){
            $response->setDump($this->curlDump);
        }
        if ($this->useCookie) {
            $response->setCookie([
                "useCookie" => $this->useCookie,
                "cookieName" => $this->cookiename,
                "cookiePath" => __DIR__ . "/cookies/" . $this->cookiename
            ]);

            if (count($this->cookieExtra) > 0) {
                $response->setExtraCookies($this->cookieExtra);
            }
        }


        if ($responseContent) {
            if ($this->interceptCookies) {
                preg_match_all('/Set-Cookie:(?<cookie>\s*.*)$/im', $responseContent, $cookies);
                $response->setResponseCookies($cookies[0]);


                $responseCookies = $response->getResponseCoookies();

                $cookiesArray = [];


                foreach ($responseCookies as $cookie) {
                    $cookie = preg_replace('/^set-cookie:\s*/i', '', $cookie);
                    $equalPos = strpos($cookie, '=');
                    if ($equalPos !== false) {
                        $cookieName = substr($cookie, 0, $equalPos);
                        $cookieValue = explode(';', substr($cookie, $equalPos + 1))[0];
                        $cookieName = trim($cookieName);
                        $cookieValue = trim($cookieValue);
                        if (!empty($cookieValue)) {
                            $cookiesArray[$cookieName] = $cookieValue;
                        }
                    }
                }

                $this->responsedCookiesRaw = array_merge($this->responsedCookiesRaw, $cookiesArray);

                $response->setResponsedCookiesRaw($this->responsedCookiesRaw);

                $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $response->setHeader(substr($responseContent, 0, $headerSize));
                $responseContent = substr($responseContent, $headerSize);
            }
        }

        $response->setProxy($this->proxy);
        $response->setRequest($this->getRequest());
        $response->setBody($responseContent);
        $response->setStatus($statusCode);

        $connectedIp = curl_getinfo($ch, CURLINFO_PRIMARY_IP);

        $response->setRemoteIP($connectedIp);


        if ($statusCode >= 300 && $statusCode <= 399) {
            $response->setRedirectUrl(curl_getinfo($ch, CURLINFO_REDIRECT_URL));

        }

        $response->setETA(microtime(true) - $this->etatime);
        return $response;
    }

    /**
     * Handles responses from multiple cURL requests.
     *
     * @param Response $response
     * @param resource $ch
     * @param string $responseContent
     * @param int $index
     */
    protected function handleMultiCurlResponse(Response $response, $ch, string $responseContent, int $index): void
    {
        $start = microtime(true);
        if ((bool) $responseContent === false) {
            $response->setError(curl_error($ch));
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response->setStatus($statusCode);

        if ($statusCode >= 300 && $statusCode <= 399) {
            $response->setRedirectUrl(curl_getinfo($ch, CURLINFO_REDIRECT_URL));
        }

        $response->setProxy($this->proxy);
        $response->range = $this->range;
        $response->setRequest($this->getRequest());
        $response->setBody($responseContent);
        $response->setETA(round(microtime(true) - $start, 3));

        if ($this->download) {
            if ($statusCode != 200) {
                $response->setError("No se encontró el fichero.");
            }
            if (!is_dir($this->downloadPath[$index])) {
                mkdir($this->downloadPath[$index], 0777);
            }

            if (file_put_contents($this->downloadPath[$index] . "/" . $this->downloadName[$index], $responseContent, FILE_APPEND) === false) {
                $response->setError("No se logró guardar el archivo");
            }
        }
    }
}
