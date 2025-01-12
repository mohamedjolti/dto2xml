<?php

namespace Dtotoxml\Tools;

use Dtotoxml\Contracts\Handler;

class Configuration
{
    private string $head = "";

    /**
     * @var Handler[] $handlers
     */
    private $handlers = [];

    private $nameSpaceOutput = '';

    public function getHead(): string
    {
        return $this->head;
    }

    public function setHead(string $head): void
    {
        $this->head = $head;
    }

    public function getHandlers(): array
    {
        return $this->handlers;
    }

    public function setHandlers(array $handlers): void
    {
        $this->handlers = $handlers;
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