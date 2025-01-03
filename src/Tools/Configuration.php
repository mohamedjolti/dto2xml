<?php

namespace Dtotoxml\Tools;

class Configuration
{
    private string $head = "";

    public function getHead(): string
    {
        return $this->head;
    }

    public function setHead(string $head): void
    {
        $this->head = $head;
    }



}