<?php

namespace Srvclick\Scurl;

trait Nip
{

    protected bool $useNip = false;
    protected string $res = '{"success":"yes"}';
    protected string $nip = "";
    public function nipSetRange($init,$end): void
    {
        $this->range = $this->getRange($init,$end);
    }
    public function NipMultiUrl($url): void
    {
        $this->multicurl = true;
        $this->useNip = true;
        foreach ($this->range as $i => $range) {
            $this->MultiUrl($url);
        }
    }
    public function NipSetParams($params): void
    {
        foreach ($this->range as $i => $range) {
            $this->setParameters( $params($range) );
        }
    }
    public function setExpectation($expectation)
    {
        if($expectation($this->res)){
            $this->nip = "123";
            return true;
        }
        return false;
    }

    public function getExpectation()
    {
        if (!empty($this->nip)) return "nip encontrado";
        return "nip no encontrado";
    }
}