<?php

namespace Samples\Dto;

class Room
{

    /**
     * @var string $number
     */
    private string $number;


    /**
     * @var string $departementId
     */
    private $departementId;

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getDepartementId(): string
    {
        return $this->departementId;
    }

    public function setDepartementId(string $departementId): void
    {
        $this->departementId = $departementId;
    }


}