<?php

namespace Tests\TestTools;

use Dtotoxml\Exception\BadArgumentException;
use Dtotoxml\Tools\Configuration;
use Dtotoxml\Tools\XmlSerializer;
use PHPUnit\Framework\TestCase;
use Samples\Dto\Adresse;
use Samples\Dto\School;
use Samples\Fixtures\SchoolFixtures;
use function PHPUnit\Framework\assertEquals;

class TestXmlSerializer extends TestCase
{


    public function testGetTagFromString()
    {
        $serializer = new XmlSerializer();
        $this->assertEquals("SCHOOL", $serializer->getTagFromString("school"));
    }

    public function testGetOpeningTag()
    {
        $serializer = new XmlSerializer();
        $this->assertEquals("<SCHOOL>", $serializer->getOpeningTag("SCHOOL"));
    }

    public function testGetClosingTag()
    {
        $serializer = new XmlSerializer();
        $this->assertEquals("</SCHOOL>", $serializer->getClosingTag("SCHOOL"));
    }

    public function testIsPropertyAnAttribute()
    {
        $serializer = new XmlSerializer();
        $addrese = new Adresse();
        $reflection = new \ReflectionClass($addrese);
        $idProperty = $reflection->getProperty("id");
        $cityProperty = $reflection->getProperty("city");
        self::assertTrue($serializer->isPropertyAnAttribute($idProperty));
        self::assertFalse($serializer->isPropertyAnAttribute($cityProperty));
    }

    public function testIsPropertyAnObjectType()
    {
        $serializer = new XmlSerializer();
        $school = new School();
        $reflection = new \ReflectionClass($school);
        $adresseProperty = $reflection->getProperty("adresse");
        $nameProperty = $reflection->getProperty("name");
        self::assertTrue($serializer->isPropertyOfObjectType($adresseProperty));
        self::assertFalse($serializer->isPropertyOfObjectType($nameProperty));
    }

    public function testIsPropertyAnArrayType()
    {
        $serializer = new XmlSerializer();
        $school = new School();
        $reflection = new \ReflectionClass($school);
        $teachersProperty = $reflection->getProperty("teachers");
        $addressedProperty = $reflection->getProperty("adresse");
        self::assertTrue($serializer->isPropertyArrayType($teachersProperty));
        self::assertFalse($serializer->isPropertyArrayType($addressedProperty));
    }

    public function testGetTagNameByAnnotationIfExist()
    {
        $serializer = new XmlSerializer();
        $address = new Adresse();
        $reflection = new \ReflectionClass($address);
        $idProperty = $reflection->getProperty("id");
        $cityProperty = $reflection->getProperty("city");
        self::assertEquals("adresse-city", $serializer->getTagNameByAnnotationIfExist($cityProperty));
        self::assertEquals("ID", $serializer->getTagNameByAnnotationIfExist($idProperty));
    }

    public function testObjectToXml()
    {
        $serializer = new XmlSerializer();
        $schoolObject = SchoolFixtures::createSchool();
        $config = new Configuration();
        $config->setHead('<?xml version="1.0"?>');
        $config->setNameSpaceOutput("Samples\Dto");
        $serializer->setConfig($config);
        $schoolXml = SchoolFixtures::createSchoolXml();
        self::assertEquals($schoolXml, $serializer->format($serializer->serialise($schoolObject)));
    }

    public function testXmlToArray()
    {
        $serializer = new XmlSerializer();
        $schoolXml = new \SimpleXMLElement(SchoolFixtures::createSchoolXml());
        $schoolArray = SchoolFixtures::createSchoolArray();
        self::assertEquals($schoolArray, $serializer->xmlToArray($schoolXml));
    }

    public function testGetClassNameByInputAttributeIfExist()
    {
        $serializer = new XmlSerializer();
        $propertyName = $serializer->getPropertyNameByInputAttributeIfExist('ADRESSE', School::class);
        self::assertSame("adresse", $propertyName);
    }

    public function testGetClassNameByPropertyName()
    {
        $serializer = new XmlSerializer();
        $className = $serializer->getClassNameByPropertyName('adresse', School::class);
        self::assertSame("Adresse", $className);
    }

    public function testGetSetterNameByPropertyName()
    {
        $serializer = new XmlSerializer();
        $setterName = $serializer->getSetterNameByPropertyName('adresse', School::class);
        self::assertSame("setAdresse", $setterName);
    }

    public function testArrayToObject()
    {
        $serializer = new XmlSerializer();
        $config = new Configuration();
        $config->setHead('<?xml version="1.0"?>');
        $config->setNameSpaceOutput("Samples\Dto");
        $serializer->setConfig($config);
        $schoolArray = SchoolFixtures::createSchoolArray();
        $schoolObject = SchoolFixtures::createSchool();
        self::assertEquals($schoolObject, $serializer->arrayToObject($schoolArray, School::class));
    }

    public function testXmlToObject()
    {
        $serializer = new XmlSerializer();
        $config = new Configuration();
        $config->setHead('<?xml version="1.0"?>');
        $config->setNameSpaceOutput("Samples\Dto");
        $serializer->setConfig($config);
        $schoolXml = SchoolFixtures::createSchoolXml();
        $schoolObject = SchoolFixtures::createSchool();
        assertEquals($schoolObject, $schoolXml);
    }
}