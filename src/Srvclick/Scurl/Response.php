<?php
namespace Srvclick\Scurl;

class Response extends Verbose
{

    protected string $body = "SCURL:Empty";
    protected int $status = 0;

    protected string $error = "";
    protected object $request;

    protected string $redirecturl;

    public function setRedirectUrl(string $url) : void{
        $this->redirecturl = $url;
    }
    public function getRedirectUrl() : string{
        return empty($this->redirecturl) ? '' : $this->redirecturl;
    }

    public function setRequest($request)
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
