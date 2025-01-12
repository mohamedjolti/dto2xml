<?php

namespace Samples\Dto;

class Adresse
{

    /**
     * @isAttribute
     * @var string $id
     */
    private string $id;

    /**
     * @outputName adresse-city
     * @inputName adresse-city
     * @var string $city
     */
    private $city;


    /**
     * @var string $city
     */
    private $street;

    /**
     * @var string $city
     */
    private $country;

    /**
     * @var string $city
     */
    private $zip;



    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip): void
    {
        $this->zip = $zip;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }


}