<?php
namespace Srvclick\Scurl;

trait Parser
{
    public function ParseCurl($code)
    {
        $lines = preg_split("/\r\n|\n|\r/", $code);
        $url = "";
        $body = "";
        $method = "GET";
        $headers = [];
        foreach ($lines as $line) {
            $line = trim($line);
            $line = str_replace("'", "\"", $line);
            if (strpos($line, 'curl') !== false) {
                preg_match('/"(.*?)"/s', $line, $matches);
                if (isset($matches[1])) {
                    $url = $matches[1];
                    // Normalizar la URL
                    $url = rtrim($url, '/');
                }
            } elseif (str_starts_with($line, '-H') || str_starts_with($line, '--header')) {
                preg_match('/"(.*?)"/s', $line, $matches);
                if (isset($matches[1])) {
                    $header = $matches[1];
                    // Evitar encabezados de seguridad
                    if (!str_starts_with(strtolower($header), 'sec-')) {
                        $headers[] = $header;
                    }
                }
            } elseif (str_starts_with($line, '--data-raw') || str_starts_with($line, '--data')) {
                preg_match('/"(.*?)"/s', trim($line), $bodyMatches);
                if (isset($bodyMatches[1])) {
                    $body = $bodyMatches[1];
                    // Determinar el método basado en la existencia del cuerpo
                    $method = empty($body) ? "GET" : "POST";
                }
            }
        }
        $text = '$curl = new Scurl_Request();' . PHP_EOL;
        $text .= '$curl->setUrl("' . addslashes($url) . '");' . PHP_EOL;
        $text .= '$curl->setMethod("' . $method . '");' . PHP_EOL;
        if ($method == "POST") {
            $text .= '$curl->setParameters("' . addslashes($body) . '");' . PHP_EOL;
        }
        if (!empty($headers)) {
            $text .= '$curl->setHeaders(array(' . PHP_EOL;
            foreach ($headers as $header) {
                $text .= '    "' . addslashes($header) . '",' . PHP_EOL;
            }
            $text .= '));' . PHP_EOL;
        }
        $text .= '$response = $curl->Send();' . PHP_EOL;
        $text .= 'if ($response->getStatus() != 200) { print_r($response); return false; }' . PHP_EOL;
        $text .= 'echo $response->getBody();' . PHP_EOL;
        return $text;
    }

}