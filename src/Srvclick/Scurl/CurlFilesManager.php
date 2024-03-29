<?php
namespace Srvclick\Scurl;

class CurlFilesManager extends Utils
{

    public function deleteCookie() : bool{
        if (empty($this->cookiename) || !is_dir(__DIR__ . "/cookies")) return false;
        if (!file_exists(__DIR__."/cookies/".$this->cookiename)) return false;
        if(!unlink(__DIR__."/cookies/".$this->cookiename)) return false;
        return true;
    }
}
