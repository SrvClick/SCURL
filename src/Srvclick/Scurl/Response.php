<?php
namespace Srvclick\Scurl;

class Response extends Verbose
{

    protected string $body = "SCURL:Empty";
    protected int $status = 0;
    protected string $error = "";
    protected object $request;
    protected string $redirecturl;
    protected array $responseCookies;
    protected object $cookies;

    public function setResponseCookies($responseCookies) : void
    {
        $this->responseCookies = $responseCookies;
    }
    public function setRedirectUrl(string $url) : void{
        $this->redirecturl = $url;
    }

    public function getResponseCoookies(): array
    {
        return $this->responseCookies;
    }
    public function getRedirectUrl() : string{
        if (empty($this->redirecturl)) return '';
        return $this->redirecturl;
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
        $this->error = $error;
    }
    public function setBody($body) : void
    {
        $this->body = $body;
    }

    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    public function getBody(): string
    {
        return $this->body;
    }
    public function getStatus(): int
    {
        return $this->status;
    }

}
