<?php

namespace Srvclick\Scurl;

trait Nip
{

    protected bool $useNip = false;
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
}