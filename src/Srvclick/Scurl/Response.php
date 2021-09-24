<?php

namespace Srvclick\Scurl;

class Response
{

    protected string $body = "SCURL:Empty";
    protected int $status = 0;

    protected string $error = "";

    public function setError(string $error){
        $this->error = $error;
    }
    public function setBody($body){
        $this->body = $body;
    }

    public function setStatus(int $status){
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
