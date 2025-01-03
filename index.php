<?php
require 'vendor/autoload.php';

use Dtotoxml\Tools\Configuration;
use Dtotoxml\Tools\XmlSerializer;
use Samples\Adresse;
use Samples\School;
use Samples\Teacher;

$xmlSerialize = new   XmlSerializer();
/** Configuration */
$config = new Configuration();
$config->setHead('<?xml version="1.0"?>');
$xmlSerialize->setConfig($config);

$school = new School();
$school->setName("Ensi tanger");
/* Adresse */
$adresse = new Adresse();
$adresse->setCity("Tangier");
$adresse->setCountry("Morocco");
$adresse->setZip("90090");
$adresse->setStreet("Casabarata");
$school->setAddress($adresse);

/* Teachers */
$teacher1  = new Teacher();
$teacher1->setName("John Doe");
$teacher1->setAge("30");
$teacher1->setGender("M");

$teacher2  = new Teacher();
$teacher2->setName("jaclyne Doe");
$teacher2->setAge("30");
$teacher2->setGender("F");

$school->setTeachers([$teacher1, $teacher2]);

$xml =  $xmlSerialize->format($xmlSerialize->serialise($school));

echo '<pre>', htmlentities($xml), '</pre>';