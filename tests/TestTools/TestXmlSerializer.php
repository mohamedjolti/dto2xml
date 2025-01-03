<?php

namespace Tests\TestTools;

use Dtotoxml\Exception\BadArgumentException;
use Dtotoxml\Tools\XmlSerializer;
use PHPUnit\Framework\TestCase;

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


}