<?php
require 'vendor/autoload.php';

use Dtotoxml\Tools\Configuration;
use Dtotoxml\Tools\XmlSerializer;
use Samples\Dto\School;
use Samples\Fixtures\SchoolFixtures;

$xmlSerialize = new   XmlSerializer();
/** Configuration */
$config = new Configuration();
$config->setHead('<?xml version="1.0"?>');
$config->setNameSpaceOutput("Samples\Dto");
$xmlSerialize->setConfig($config);

$school = SchoolFixtures::createSchool();
$xml =  $xmlSerialize->format($xmlSerialize->serialise($school));

echo '<pre>', htmlentities($xml), '</pre>';

$xmlString = SchoolFixtures::createSchoolXml();
$object = $xmlSerialize->unserialise($xmlString, School::class);
