
# SCURL

Easy PHP CURL Library





## Installation


```bash
composer require srvclick/scurl
```


### IMPORT
```php
use Srvclick\Scurl\Scurl_Request as SCURL;
```
### Examples
#### GET REQUEST

```php
$curl = new SCURL;
$curl->setUrl('https://example.com');
$response = $curl->Send();
echo $response->getBody();
```

#### POST REQUEST

```php
$curl->setMethod("POST");
$curl->setParameters([
    'user' => 'user',
    'password' => 'password',
]);

```


### Custom Method

```php
$curl->setConfigs([
    'custom_method' => 'PUT'
])
```

#### SET PROXY

###### WITHOUT AUTH
```php

$curl->setProxy([
    'proxy' => '127.0.0.1',
    'proxy_port' => '9090'
]);
```
###### WITH AUTH
```php
$curl->setProxy([
    'proxy' => '127.0.0.1',
    'proxy_port' => '9090',
    'proy_user' => 'root',
    'proxy_pass' => 'toor'
]);
```

### SET USER-AGENT

###### Option 1
```php
$curl->setConfigs([
    'user-agent' => 'Mozilla'
])
```
###### Option 2
```php
$curl->setHeaders([
    'user-agent: Mozilla'
])
```

#### SSL VERIFY PEER
```php
$curl->setConfigs([
    'ssl_verifypeer' => 'false'
]);
```

#### USE COOKIES
```php
$this->curl->useCookie(true);
$curl->setCookieName('Random Cookie Name');
```

#### DOWNLOAD FILES
```php
$curl->downloadFile("/path/","filename.ext");
```

#### INTERCEPT COOKIES
```php
$curl->setInterceptCookie(true);
$responseCookies = $response->getResponseCoookies(); //Array
```


#### AVAILABLE CONFIGURATIONS
```php
$curl->setConfigs([
    'use_proxy' => true,
    'max_redirs' => 10,
    'timeout' => 30,
    'http_version' => CURL_HTTP_VERSION_1_1,
    'return_transfer' => true,
    'ssl_verifypeer' => true,
    'follow' => false,
    'encondig' => "",
    'user-agent' => 'SCURL by SrvClick',
    'header' => [],
    'http_auth' => true,
    'http_user' => 'admin',
    'http_pass' => 'admin'
]);
```



#### MULTI CURL SUPPORT
```php
$curl = new SCURL();
$curl->setMulticurl();
for ($i = 0; $i < 10; $i++) {
    $curl->MultiUrl('https://jsonplaceholder.typicode.com/todos/'.$i);
    $curl->downloadFile(__DIR__."/downloads/","item_".$i.".json");
}
$response = $curl->Send();
for ($i = 0; $i < $response->getCount(); $i++) {
    echo "Peticion ".$i." Con HTTP STATUS ".$response->getStatus($i)."\n";
}
```

### NIP SUPPORT
```php

$curl->NipSetRange($core, $limit);
$curl->NipMultiUrl('https://example.com/nip');
$curl->NipSetParams(function($nip){
        return ['otp' => str_pad($nip ,4,0,STR_PAD_LEFT)];
        //return '{"nip":"'.str_pad($nip ,4,0,STR_PAD_LEFT).'"}'; //Example 2
    }
$response = $curl->Send();

if($response->checkNip(function ($response) {
    $decode = json_decode($response,true);
    if ($decode['success'] == "yes") return true;
    return false;
})){
    echo "NIP: ".$response->getNip()."\n";
}else{
    echo "Nip not found\n";
}
);


```
