<?php

namespace Samples\Dto;

class School
{

    /**
     * @var string $name
     */
    private $name;


    /**
     * @var Adresse $adresse
     */
    private $adresse;

    /**
     * @var Teacher[] $teachers
     */
    private $teachers;


    /**
     * @outputName school-rooms
     * @inputName school-roomsH
     * @var Room[] $rooms
     */
    private $rooms;

    public function getAttributes():array
    {
        return [
          "name"
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAdresse(): Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(Adresse $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getTeachers(): array
    {
        return $this->teachers;
    }

    public function setTeachers(array $teachers): void
    {
        $this->teachers = $teachers;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function setRooms(array $rooms): void
    {
        $this->rooms = $rooms;
    }


}