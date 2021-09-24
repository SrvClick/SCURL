<?php
namespace Srvclick\Scurl;

class Response
{

    protected string $body = "SCURL:Empty";
    protected int $status = 0;

    protected string $error = "";

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
