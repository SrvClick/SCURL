<?php
namespace Srvclick\Scurl;

class Verbose
{
    public function verbose() : object
    {



        return (Object) array(
                'Request' => (Object) [
                'URL' => $this->request->url,
                'METHOD' => $this->request->method,
                'PARAMETERS' => $this->request->parameters,
                'CONFIGS' => (Object) $this->request->configs,
                'OPTIONS' => (Object) $this->request->options,
            ],
            'RESPONSE' => (Object) [
                'BODY' => $this->body,
                'HTTP_CODE' => $this->status,
                'ERROR' => $this->error,

            ]
        );
    }

}
