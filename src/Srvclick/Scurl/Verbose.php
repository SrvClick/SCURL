<?php
namespace Srvclick\Scurl;

use PhpParser\Node\Expr\Cast\Object_;

trait Verbose
{

    public function verbose() : Void
    {
        echo "\n\n**** SRVCLICK VERBOSE RESPONSE ****\n\n";

        //print_r($this->request);

        $verbose = (array(
                'Request' => (Object) [
                    'URL' => $this->request->url,
                    'METHOD' => $this->request->method,
                    'PARAMETERS' => $this->request->parameters,
                    'CONFIGS' => (Object) $this->request->configs,
                    'OPTIONS' => (Object) $this->request->options,
                    "PROXY" => $this->proxy,
                    'HEADERS' => $this->request->headers,
                    'COOKIEEXTRA' => $this->cookieExtra
                ],
            'RESPONSE' => (Object) [
                'BODY' => $this->body,
                'HTTP_CODE' => $this->status,
                'ERROR' => $this->error ?? null,
                'REDIRECT_URL' => empty($this->redirecturl) ? '' : $this->redirecturl,
                "ETATIME" => $this->etatime,
                "COOKIES" => $this->cookies ?? "nothing",
                'HEADERS' => $this->request->headers ?? "nothing",
            ]
        )
        );


        print_r($verbose);
    }
}
