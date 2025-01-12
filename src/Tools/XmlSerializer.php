<?php

namespace Dtotoxml\Tools;

use Dtotoxml\Contracts\Serialiser;
use Dtotoxml\Exception\BadArgumentException;
use Dtotoxml\Exception\BadPropertyException;
use Dtotoxml\Exception\NotFoundException;
use Dtotoxml\Properties\ObjectProperties;
use Dtotoxml\Properties\XmlProperties;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use DOMDocument;
use SimpleXMLElement;

class XmlSerializer implements Serialiser
{

    private Configuration $config;

    /**
     * @param $dto
     * @return string
     * @throws BadArgumentException
     * @throws ReflectionException
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
            if ($this->isPropertyAnAttribute($property)) {
                $propertyTag = $this->getTagNameByAnnotationIfExist($property);
                $attributes .= $propertyTag . " " . XmlProperties::XML_CLOSING_EQAUL . "'" . $propertyValue . "' " . "\n";
            } /** Check if the annotation of the property is an array of objects */
            elseif ($this->isPropertyArrayType($property) && is_array($propertyValue)) {
                $xml .= $this->serializeArrayObject($property, $propertyValue);
            } /** Check if the annotation of the property is an Object */
            elseif ($this->isPropertyOfObjectType($property) && is_object($propertyValue)) {
                $xml .= $this->serialise($propertyValue, false);
            } else {
                $propertyTag = $this->getTagNameByAnnotationIfExist($property);
                $xml .= $this->getOpeningTag($propertyTag) . $propertyValue . $this->getClosingTag($propertyTag);
            }
        }

        $header = ($isParent && $this->getConfig() instanceof Configuration ? $this->getConfig()->getHead() : "");
        return $header . $this->getOpeningTagWithAttributes($tag, $attributes) . $xml . $closingTag;
    }

    public function unserialise(string $xml, $targetClass)
    {
        if (!class_exists($targetClass)) {
            throw new NotFoundException("The target class '$targetClass' does not exist");
        }
        $xmlELement = new SimpleXMLElement($xml);
        $array = $this->xmlToArray($xmlELement);
        $instance = $this->arrayToObject($array, $targetClass);
        return $instance;
    }

    private function xmlToArray(SimpleXMLElement $xml): array|string
    {
        $array = [];

        // Include attributes
        foreach ($xml->attributes() as $attrKey => $attrValue) {
            $array[XmlProperties::XML_ATTRIBUTE_UNSERIALIZE_KEY_NAME][$attrKey] = (string)$attrValue;
        }

        // Process child elements
        foreach ($xml->children() as $key => $value) {
            $child = $this->xmlToArray($value);

            // Handle multiple sibling elements with the same key
            if (isset($array[$key])) {
                if (!is_array($array[$key]) || !isset($array[$key][0])) {
                    $array[$key] = [$array[$key]]; // Convert existing value to array
                }
                $array[$key][] = $child;
            } else {
                $array[$key] = $child;
            }
        }

        // If no children and no attributes, return the node value as an array
        if (empty($array)) {
            return (string)$xml;
        }

        return $array;
    }


    private function arrayToObject($array, $targetClass)
    {
        $instance = new $targetClass();
        foreach ($array as $key => $value) {

            /** List of attributes */
            if (XmlProperties::XML_ATTRIBUTE_UNSERIALIZE_KEY_NAME == $key) {
                foreach ($value as $attribute => $attributeValue) {
                    $attributePropertyName = $this->getClassNameByInputAttributeIfExist($attribute, $targetClass);
                    $setterName = $this->getSetterNameByPropertyName($attributePropertyName);
                    if (!$setterName) {
                        throw new NotFoundException("The property '" . $attribute . "' does not exist or doesn't have a setter method");
                    }
                    $instance->$setterName($attributeValue);
                }
                continue;
            }
            $propertyName = $this->getClassNameByInputAttributeIfExist($key, $targetClass);
            $setterName = $this->getSetterNameByPropertyName($propertyName);
            /** If it is a sub type */
            if (is_array($value)) {
                $class = $this->getClassNameByPropertyName($propertyName, $targetClass);
                if (!$class) {
                    continue;
                }
                $className = $this->config->getNameSpaceOutput() . '\\' . $class;
                /** If it is a sub type of type array */
                if (str_contains($className, ObjectProperties::PROPERTY_TYPE_ARRAY)) {
                    $arrayObjects = $this->getArrayOfObjectsFromValues($value, $className);
                    $instance->{$setterName}($arrayObjects);
                } else {
                    $object = $this->arrayToObject($value, $className);
                    $instance->{$setterName}($object);
                }
            } else {
                $instance->{$setterName}($value);
            }
        }
        return $instance;
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
    public function getOpeningTag(string $tag):string
    {
        return XmlProperties::XML_OPENING_ARROW . $tag . XmlProperties::XML_CLOSING_ARROW;
    }

    /**
     * @param string $tag
     * @return string
     */
    public function getClosingTag(string $tag):string
    {
        return XmlProperties::XML_OPENING_ARROW . XmlProperties::XML_SLASH . $tag . XmlProperties::XML_CLOSING_ARROW;
    }

    /**
     * @param $dto
     * @return mixed|string
     * @throws \ReflectionException
     */
    private function getTagFromDto($dto):string
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
     * @param string $propertyName
     * @return mixed|string
     * @throws \ReflectionException
     */
    public function getTagFromString(string $propertyName):string
    {
        return strtoupper($propertyName);
    }

    /**
     * @param ReflectionProperty $property
     * @return string
     */
    public function getGetterMethodName(ReflectionProperty $property): string
    {
        $prertyName = "get" . ucfirst(strtolower($property->getName()));
        return $prertyName;
    }

    /**
     * @param $xml
     * @return false|string
     */
    public function format($xml):string
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml);
        $out = $dom->saveXML();
        return $out;
    }

    public function isPropertyOfObjectType(ReflectionProperty $property):bool
    {
        $docComment = $property->getDocComment();

        // Check for the presence of the @var annotation
        if (preg_match(ObjectProperties::PROPERTY_VAR_REGEX_NAME, $docComment, $matches)) {
            $type = $matches[1];

            // Check if the type is an object (not a scalar type like int, string, etc.)
            if (!in_array(strtolower($type), ObjectProperties::SCALAR_VALUES)) {
                return true;
            }
        }

        return false;
    }

    public function isPropertyArrayType(ReflectionProperty $property):bool
    {
        $docComment = $property->getDocComment();

        // Check for the presence of the @var annotation with the [] array syntax
        if (preg_match(ObjectProperties::PROPERTY_VAR_ARRAY_REGEX_NAME, $docComment, $matches)) {
            return true;
        }

        return false;
    }

    /**
     * @parm ReflectionProperty $property
     * @param array $propertyValue
     * @return string
     * @throws BadArgumentException
     * @throws ReflectionException
     */
    private function serializeArrayObject(ReflectionProperty $property, array $propertyValue):string
    {
        $propertyTag = $this->getTagNameByAnnotationIfExist($property);
        $xml = $this->getOpeningTag($propertyTag);
        foreach ($propertyValue as $value) {
            if (is_object($value)) {
                $xml .= $this->serialise($value, false);
            }
        }
        return $xml . $this->getClosingTag($propertyTag);
    }

    /**
     * @param $propertyName
     * @return bool
     */
    public function isPropertyAnAttribute(ReflectionProperty $reflectionProperty)
    {
        $docComment = $reflectionProperty->getDocComment();

        if ($docComment) {
            // Match the @tagName annotation using a regular expression
            if (preg_match(XmlProperties::XML_ATTRIBUTE_ANNOTATION, $docComment, $matches)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function getOpeningTagWithAttributes($tag, string $attributes)
    {
        $attributesString = $attributes ? " " . $attributes : "";
        return XmlProperties::XML_OPENING_ARROW . $tag . $attributesString . XmlProperties::XML_CLOSING_ARROW;
    }

    /**
     * @param ReflectionProperty $reflectionProperty
     * @return string
     */
    public function getTagNameByAnnotation($reflectionProperty)
    {
        $docComment = $reflectionProperty->getDocComment();

        if ($docComment) {
            // Match the @tagName annotation using a regular expression
            if (preg_match(ObjectProperties::PROPERRTY_OUTPUT_REGEX_NAME, $docComment, $matches)) {
                $tagValue = $matches[1];
                return $tagValue;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getTagNameByAnnotationIfExist(ReflectionProperty $property): string
    {
        $tagNameByAnnotation = $this->getTagNameByAnnotation($property);
        if ($tagNameByAnnotation) {
            $propertyTag = $tagNameByAnnotation;
        } else {
            $propertyTag = $this->getTagFromString($property->getName());
        }
        return $propertyTag;
    }

    private function getClassNameByInputAttributeIfExist(string $child, string $targetClass): string
    {
        $reflection = new ReflectionClass($targetClass);
        $preperties = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);
        foreach ($preperties as $property) {
            $docComment = $property->getDocComment();
            if ($docComment) {
                // Match the @tagName annotation using a regular expression
                if (preg_match(ObjectProperties::PROPERRTY_INPUT_REGEX_NAME, $docComment, $matches)) {
                    $tagValue = $matches[1];
                    if ($tagValue == $child) {
                        return $property->getName();
                    }
                }
            }
        }
        return strtolower($child);
    }


    /**
     *
     * @param string $propertyName
     * @param $targetClass
     * @return false|string
     * @throws ReflectionException
     */
    private function getClassNameByPropertyName(string $propertyName, string $targetClass)
    {
        $reflectionClass = new ReflectionClass($targetClass);
        if (!$reflectionClass->hasProperty($propertyName)) {
            throw new NotFoundException("Property $propertyName does not exist in $targetClass");
        }
        $property = $reflectionClass->getProperty($propertyName);
        $docComment = $property->getDocComment();

        // Check for the presence of the @var annotation
        if (preg_match(ObjectProperties::PROPERTY_VAR_REGEX_NAME, $docComment, $matches)) {
            $type = $matches[1];

            // Check if the type is an object (not a scalar type like int, string, etc.)
            if (!in_array(strtolower($type), ObjectProperties::SCALAR_VALUES)) {
                return $type;
            }
        }
        return false;
    }

    private function getSetterNameByPropertyName(string $propertyName)
    {
        return 'set' . ucfirst($propertyName);
    }

    private function getArrayOfObjectsFromValues(array $value, string $className):array
    {
        $className = str_replace(ObjectProperties::PROPERTY_TYPE_ARRAY, '', $className);
        $arrayObjects = [];
        $items = array_values($value)[0];
        foreach ($items as $item) {
            $object = $this->arrayToObject($item, $className);
            $arrayObjects[] = $object;
        }
        return $arrayObjects;
    }

}