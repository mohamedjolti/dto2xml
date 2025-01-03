<?php

namespace Dtotoxml\Tools;

use Dtotoxml\Contracts\Serialiser;
use Dtotoxml\Exception\BadArgumentException;
use Dtotoxml\Exception\BadPropertyException;
use Dtotoxml\Properties\XmlProperties;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use DOMDocument;

class XmlSerializer implements Serialiser
{

    private Configuration $config;

    /**
     * @param $dto
     * @return string
     * @throws BadArgumentException
     * @throws \ReflectionException
     */
    public function serialise($dto, $isParent = true)
    {
        /* Verifiy that  the argument of type object */
        if (!is_object($dto)) {
            throw new BadArgumentException("Invalid dto");
        }
        $reflection = new ReflectionClass($dto);
        $preperties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        $tag = $this->getTagFromDto($dto);
        $closingTag = $this->getClosingTag($tag);
        $xml = '';
        $attributes = "";

        /**
         * For each property we will check if the property is a dto/array/value
         */
        foreach ($preperties as $property) {
            $prepertyGetterMethod = $this->getGetterMethodName($property);
            $prepertyName = $property->getName();
            /** Check if the property has a getter method  */
            if (!method_exists($dto, $prepertyGetterMethod)) {
                throw new BadPropertyException("The property '" . $prepertyName . "' of the object " . $reflection->getName() . " does not have a getter method");
            }
            $propertyValue = $dto->{$prepertyGetterMethod}();

            /** Check if the property is an attribute */
            if($this->isPropertyAnAttributes($prepertyName, $dto)) {
                $attributes .= $prepertyName . " ". XmlProperties::XML_CLOSING_EQAUL ."'" . $propertyValue . "' ". "\n";
            }
            /** Check if the annotation of the property is an array of objects */
            elseif ($this->isPropertyArrayType($reflection, $prepertyName) && is_array($propertyValue)) {
                $xml .= $this->serializeArrayObject($propertyValue, $prepertyName);
            }
            /** Check if the annotation of the property is an Object */
            elseif ($this->isPropertyOfObjectType($reflection, $prepertyName) && is_object($propertyValue)) {
                $xml .= $this->serialise($propertyValue, false);
            }

            else {
                $propertyTag = $this->getTagFromString($prepertyName);
                $xml .= $this->getOpeningTag($propertyTag) . $propertyValue . $this->getClosingTag($propertyTag);
            }
        }

        $header = ($isParent && $this->getConfig() instanceof Configuration ? $this->getConfig()->getHead() : "");
        return $header . $this->getOpeningTagWithAttributes($tag, $attributes) . $xml . $closingTag;
    }

    public function unserialise($xml, $targetClass)
    {
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }

    public function setConfig(Configuration $config): void
    {
        $this->config = $config;
    }



    /**
     * @param string $tag
     * @return string
     */
    public function getOpeningTag($tag)
    {
        return XmlProperties::XML_OPENING_ARROW . $tag . XmlProperties::XML_CLOSING_ARROW;
    }

    /**
     * @param string $tag
     * @return string
     */
    public function getClosingTag($tag)
    {
        return XmlProperties::XML_OPENING_ARROW . XmlProperties::XML_SLASH . $tag . XmlProperties::XML_CLOSING_ARROW;
    }

    /**
     * @param $dto
     * @return mixed|string
     * @throws \ReflectionException
     */
    private function getTagFromDto($dto)
    {
        /** If the client add the method to return the tag name  we can use if not we base on the name of the class */
        $methodName = XmlProperties::XML_GETTER_TAG_NAME;
        if (method_exists($dto, $methodName)) {
            return $dto->$methodName();
        }
        $reflect = new ReflectionClass($dto);
        return strtoupper($reflect->getShortName());
    }

    /**
     * @param string $string
     * @return mixed|string
     * @throws \ReflectionException
     */
    public function getTagFromString($string)
    {
        return strtoupper($string);
    }

    /**
     * @param ReflectionProperty $property
     * @return string
     */
    public function getGetterMethodName($property)
    {
        $prertyName = "get" . ucfirst(strtolower($property->getName()));
        return $prertyName;
    }

    /**
     * @param $xml
     * @return false|string
     */
    public function format($xml)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $out = $dom->saveXML();
        return $out;
    }

    public function isPropertyOfObjectType($reflectionClass, $propertyName)
    {
        $property = $reflectionClass->getProperty($propertyName);
        $docComment = $property->getDocComment();

        // Check for the presence of the @var annotation
        if (preg_match('/@var\s+([^\s]+)/', $docComment, $matches)) {
            $type = $matches[1];

            // Check if the type is an object (not a scalar type like int, string, etc.)
            if (!in_array(strtolower($type), ['int', 'float', 'string', 'bool', 'array', 'null'])) {
                return true;
            }
        }

        return false;
    }

    public function isPropertyArrayType($reflectionClass, $propertyName)
    {
        $property = $reflectionClass->getProperty($propertyName);
        $docComment = $property->getDocComment();

        // Check for the presence of the @var annotation with the [] array syntax
        if (preg_match('/@var\s+([^\s]+)\[\]/', $docComment, $matches)) {
             return true;
        }

        return false;
    }

    /**
     * @param array $propertyValue
     * @param string $propertyName
     * @return string
     * @throws BadArgumentException
     * @throws ReflectionException
     */
    private function serializeArrayObject(array $propertyValue, string $propertyName)
    {
        $xml = $this->getOpeningTag($this->getTagFromString($propertyName));
        foreach ($propertyValue as $value) {
            if(is_object($value)){
                $xml .= $this->serialise($value, false);
            }
        }
        return $xml . $this->getClosingTag($this->getTagFromString($propertyName));
    }

    /**
     * @param $propertyName
     * @param $dto
     * @return bool
     */
    private function isPropertyAnAttributes($propertyName, $dto)
    {
        $methodName = XmlProperties::XML_GETTER_ATTRIBUTES_NAME;
        if (method_exists($dto, $methodName)) {
            $arrayAttributes =  $dto->$methodName();
            return in_array($propertyName, $arrayAttributes);
        }
        return false;
    }

    private function getOpeningTagWithAttributes($tag, string $attributes)
    {
        $attributesString = $attributes ? " ". $attributes : "";
        return XmlProperties::XML_OPENING_ARROW . $tag . $attributesString . XmlProperties::XML_CLOSING_ARROW;
    }



}