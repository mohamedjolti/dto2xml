<?php

namespace Tests\TestTools;

use Dtotoxml\Exception\BadArgumentException;
use Dtotoxml\Tools\XmlSerializer;
use PHPUnit\Framework\TestCase;
use Samples\Dto\Adresse;
use Samples\Dto\School;

class TestXmlSerializer extends TestCase
{

    public function testSerializeWithArgumentNotObject(){

        $this->expectException(BadArgumentException::class);
        $serializer = new XmlSerializer();
        $xml = $serializer->serialise("string");
    }

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

    public function testGetClosingTag(){
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
        $adresseProperty = $reflection->getProperty("adresse");
        self::assertTrue($serializer->isPropertyArrayType($teachersProperty));
        self::assertFalse($serializer->isPropertyArrayType($adresseProperty));
    }

    public function testGetTagNameByAnnotationIfExist()
    {
        $serializer = new XmlSerializer();
        $addrese = new Adresse();
        $reflection = new \ReflectionClass($addrese);
        $idProperty = $reflection->getProperty("id");
        $cityProperty = $reflection->getProperty("city");
        self::assertEquals("adresse-city", $serializer->getTagNameByAnnotationIfExist($cityProperty));
        self::assertEquals("ID", $serializer->getTagNameByAnnotationIfExist($idProperty));
    }
}