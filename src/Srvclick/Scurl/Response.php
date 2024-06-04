<?php
namespace Srvclick\Scurl;

class Response
{
    use Verbose;
    protected array $body = [];
    protected array $status = [];
    protected array $error = [];
    protected object $request;
    protected array $redirecturl = [];
    protected array $responseCookies;
    protected object $cookies;
    protected array $etatime;
    protected array $proxy;

    protected string $headers;


    protected array $multiClient = [];

    public function getCount(): int
    {
        return count($this->request->url);

    }

    public function setHeader($headers) : void{
        $this->headers = $headers;
    }
    public function setProxy($proxy) : void
    {
        $this->proxy = $proxy;
    }
    public function setResponseCookies($responseCookies) : void
    {
        $this->responseCookies[] = $responseCookies;
    }
    public function setRedirectUrl(string $url) : void{
        $this->redirecturl[] = $url;
    }

    public function getResponseCoookies($i=0): array
    {
        return $this->responseCookies[$i];
    }
    public function getRedirectUrl($i = 0) : string{
        if (empty($this->redirecturl)) return '';
        return $this->redirecturl[$i];
    }

    public function setCookie($cookies) : void{
        $this->cookies = (Object) $cookies;
    }

    public function setRequest($request) : void
    {
        $this->request = (object) $request;
    }

    public function setError(string $error) : void
    {
        $this->error[] = $error;
    }
    public function setBody($body) : void
    {
        $this->body[] = $body;
    }

    public function setStatus(int $status) : void
    {
        $this->status[] = $status;
    }
    public function getBodyArray($i = 0) : array{
        $response = json_decode($this->body[$i], true);
        if (json_last_error() !== JSON_ERROR_NONE) return ["Error" => "Error al decodificar JSON: " . json_last_error_msg()];
        if (!is_array($response)) return ["Error" => "La respuesta no es un array."];
        return $response;

    }
    public function getBody($i=0): string
    {
        return $this->body[$i];
    }
    public function getStatus($i=0): int
    {

        if (!isset($this->status[$i])) return 0;
        return $this->status[$i];
    }
    public function setETA($eta) : float
    {
        return $this->etatime[] = $eta;
    }
    public function getETA($i=0) : float{
        return round($this->etatime[$i],3);
    }

}

