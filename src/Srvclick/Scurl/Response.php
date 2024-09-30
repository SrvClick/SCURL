<?php
namespace Srvclick\Scurl;

use PhpParser\Node\Expr\Cast\Object_;

class Response
{
    use Verbose;
    public array $range, $body, $status, $error, $redirecturl, $multiClient, $etatime, $responseCookies = [];
    protected object $request, $cookies;
    protected array $proxy;
    protected $headers;
    protected ?array $cookieExtra = null;
    protected ?string $remoteIP= null;
    protected ?array $responsedCookiesRaw = null;



    protected ?string $nipResponse = null;
    protected ?string $nip = null;

    public function getResponsedCookiesRaw(): ?array
    {
        return $this->responsedCookiesRaw;
    }
    public function setResponsedCookiesRaw(array $cookies) : void
    {
        $this->responsedCookiesRaw = $cookies;
    }
    public function setExtraCookies(array $cookieExtra) : void
    {
        $this->cookieExtra = (array) $cookieExtra;
    }
    public function setRemoteIP(string $remoteIP) : void
    {
        $this->remoteIP = $remoteIP;
    }

    public function getRemoteIP() : ?string
    {
        return $this->remoteIP;
    }
    public function getRequestHeaders()
    {
     return  $this->request->header ;
    }
    public function checkNip($expectation): bool
    {
        for ($i = 0; $i < $this->getCount(); $i++){
            if (empty($this->getBody($i))) continue;
            if($expectation($this->getBody($i))){
                $this->nip = $this->range[$i];
                $this->nipResponse = $this->getBody($i);
                return true;
            }
        }
        return false;
    }
    public function getNipResponse() : ?string
    {
        return $this->nipResponse;
    }
    public function getCount(): int
    {
        return count($this->request->url);
    }

    public function getNip() : string
    {
        return $this->nip;
    }
    public function setHeader($headers) : void{
        $this->headers = $headers;
    }
    public function getHeaders() : string
    {
        return $this->headers;
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
        if (isset($this->request)) unset($this->request);
        $this->request = (object) $request;
    }

    public function setError(string $error) : void
    {
        $this->error[] = $error;
    }

    public function getError($i = 0) : string
    {
        return $this->error[$i] ?? "No definido";
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

