<?php
require 'vendor/autoload.php';

use Dtotoxml\Tools\Configuration;
use Dtotoxml\Tools\XmlSerializer;
use Samples\Dto\Adresse;
use Samples\Dto\Room;
use Samples\Dto\School;
use Samples\Dto\Teacher;

$xmlSerialize = new   XmlSerializer();
/** Configuration */
$config = new Configuration();
$config->setHead('<?xml version="1.0"?>');
$config->setNameSpaceOutput("Samples\Dto");
$xmlSerialize->setConfig($config);

$school = new School();
$school->setName("Ensi tanger");
/* Adresse */
$adresse = new Adresse();
$adresse->setId("100");
$adresse->setCity("Tangier");
$adresse->setCountry("Morocco");
$adresse->setZip("90090");
$adresse->setStreet("Casabarata");
$school->setAdresse($adresse);

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
/**  Romms  */
$room1 = new Room();
$room1->setNumber("R1");
$room1->setDepartementId("D1");

$room2 = new Room();
$room2->setNumber("R2");
$room2->setDepartementId("D2");
$school->setRooms([$room1, $room2]);
$xml =  $xmlSerialize->format($xmlSerialize->serialise($school));

echo '<pre>', htmlentities($xml), '</pre>';

$xmlString = '<?xml version="1.0"?>
<SCHOOL>
  <NAME>Ensi tanger</NAME>
  <ADRESSE ID="100">
    <adresse-city>Tangier</adresse-city>
    <STREET>Casabarata</STREET>
    <COUNTRY>Morocco</COUNTRY>
    <ZIP>90090</ZIP>
  </ADRESSE>
  <TEACHERS>
    <TEACHER>
      <NAME>John Doe</NAME>
      <AGE>30</AGE>
      <GENDER>M</GENDER>
    </TEACHER>
    <TEACHER>
      <NAME>jaclyne Doe</NAME>
      <AGE>30</AGE>
      <GENDER>F</GENDER>
    </TEACHER>
  </TEACHERS>
  <school-rooms>
    <ROOM>
      <NUMBER>R1</NUMBER>
      <DEPARTEMENTID>D1</DEPARTEMENTID>
    </ROOM>
    <ROOM>
      <NUMBER>R2</NUMBER>
      <DEPARTEMENTID>D2</DEPARTEMENTID>
    </ROOM>
  </school-rooms>
</SCHOOL>
';
$object = $xmlSerialize->unserialise($xmlString, School::class);
dd($school , $object);