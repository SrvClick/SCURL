<?php
namespace Srvclick\Scurl;

trait Verbose
{

    public function verbose() : Void
    {
        echo "\n\n**** SRVCLICK VERBOSE RESPONSE ****\n\n";

        print_r($this->request);

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
                'ERROR' => $this->error ?? null,
                'REDIRECT_URL' => empty($this->redirecturl) ? '' : $this->redirecturl,
                "ETATIME" => $this->etatime,
                "COOKIES" => $this->cookies ?? "nothing",

            ]
        )
        );
    }
}
