<?php

namespace Dtotoxml\Tools;

class Configuration
{
    private string $head = "";

    private $nameSpaceOutput = '';

    public function getHead(): string
    {
        return $this->head;
    }

    public function setHead(string $head): void
    {
        $this->head = $head;
    }

    public function getNameSpaceOutput(): string
    {
        return $this->nameSpaceOutput;
    }

    public function setNameSpaceOutput(string $nameSpaceOutput): void
    {
        $this->nameSpaceOutput = $nameSpaceOutput;
    }


}