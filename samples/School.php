<?php

namespace Samples;

class School
{

    /**
     * @var string $name
     */
    private $name;


    /**
     * @var Adresse $address
     */
    private $address;

    /**
     * @var Teacher[] $teachers
     */
    private $teachers;

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

    public function getAddress(): Adresse
    {
        return $this->address;
    }

    public function setAddress(Adresse $address): void
    {
        $this->address = $address;
    }

    public function getTeachers(): array
    {
        return $this->teachers;
    }

    public function setTeachers(array $teachers): void
    {
        $this->teachers = $teachers;
    }


}