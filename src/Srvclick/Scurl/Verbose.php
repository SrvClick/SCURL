<?php
namespace Srvclick\Scurl;

class Verbose
{

    public function verbose() : Void
    {
        echo "\n\n**** SRVCLICK VERBOSE RESPONSE ****\n\n";
        print_r(array(
                'Request' => (Object) [
                'URL' => $this->request->url,
                'METHOD' => $this->request->method,
                'PARAMETERS' => $this->request->parameters,
                'CONFIGS' => (Object) $this->request->configs,
                'OPTIONS' => (Object) $this->request->options,
                "PROXY" => $this->proxy,
            ],
            'RESPONSE' => (Object) [
                'BODY' => $this->body,
                'HTTP_CODE' => $this->status,
                'ERROR' => $this->error,
                'REDIRECT_URL' => empty($this->redirecturl) ? '' : $this->redirecturl,
                "ETATIME" => $this->etatime,
                "COOKIES" => $this->cookies ?? "nothing",

            ]
        )
        );
    }
}
