<?php

namespace Samples\Fixtures;

use Samples\Dto\Adresse;

class AdresseFixtures
{
    public static function createAdresses(): Adresse
    {
        $adresse = new Adresse();
        $adresse->setId("100");
        $adresse->setCity("Tangier");
        $adresse->setCountry("Morocco");
        $adresse->setZip("90090");
        $adresse->setStreet("Casabarata");
        return $adresse;
    }
}