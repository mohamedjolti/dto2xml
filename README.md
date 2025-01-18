# XML Serializer Package

This package provides a simple and efficient way to serialize DTOs (Data Transfer Objects) with subtypes to XML and deserialize XML back to PHP objects.

## Features
- Serialize complex PHP objects, including subtypes, into XML format.
- Deserialize XML strings back into PHP objects.
- Configurable XML headers and namespace output.

## Installation

You can install the package via Composer:

```bash
composer require jolti/dtotoxml
```

## Usage

### Setting Up the Serializer

```php
use YourVendor\XmlSerializer;
use YourVendor\Configuration;
use Samples\Dto\School;
use Samples\Dto\SchoolFixtures;

$xmlSerialize = new XmlSerializer();

/** Configuration */
$config = new Configuration();
$config->setHead('<?xml version="1.0"?>');
$config->setNameSpaceOutput("Samples\\Dto");
$xmlSerialize->setConfig($config);
```

### Serializing an Object to XML

```php
$school = SchoolFixtures::createSchool();
$xml = $xmlSerialize->format($xmlSerialize->serialise($school));

// Output the XML
echo '<pre>', htmlentities($xml), '</pre>';
```

### Deserializing XML to an Object

```php
$xmlString = SchoolFixtures::createSchoolXml();
$object = $xmlSerialize->unserialise($xmlString, School::class);

// Access the deserialized object
var_dump($object);
```

## Example DTO: School

Below is an example of a DTO that can be serialized and deserialized using this package:

```php
namespace Samples\Dto;

class School
{
    /**
     * @var string $name
     */
    private $name;

    /**
     * @var Adresse $adresse
     */
    private $adresse;

    /**
     * @var Teacher[] $teachers
     */
    private $teachers;

    /**
     * @outputName school-rooms
     * @inputName school-rooms
     * @var Room[] $rooms
     */
    private $rooms;

    public function getAttributes(): array
    {
        return ["name"];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAdresse(): Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(Adresse $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getTeachers(): array
    {
        return $this->teachers;
    }

    public function setTeachers(array $teachers): void
    {
        $this->teachers = $teachers;
    }

    public function getRooms(): array
    {
        return $this->rooms;
    }

    public function setRooms(array $rooms): void
    {
        $this->rooms = $rooms;
    }
}
```

## Configuration Options

The `Configuration` class allows you to customize the serialization process:

- `setHead(string $header)`: Set the XML header.
- `setNameSpaceOutput(string $namespace)`: Define the namespace used in the output XML.

## Testing

You can write unit tests to validate the serialization and deserialization processes:

```php
use PHPUnit\Framework\TestCase;
use YourVendor\XmlSerializer;
use Samples\Dto\SchoolFixtures;

class XmlSerializerTest extends TestCase
{
    public function testSerialization()
    {
        $serializer = new XmlSerializer();
        $school = SchoolFixtures::createSchool();
        $xml = $serializer->serialise($school);
        $this->assertNotEmpty($xml);
    }

    public function testDeserialization()
    {
        $serializer = new XmlSerializer();
        $xmlString = SchoolFixtures::createSchoolXml();
        $object = $serializer->unserialise($xmlString, School::class);
        $this->assertInstanceOf(School::class, $object);
    }
}
```

## Contributing

Contributions are welcome! Please submit issues and pull requests via GitHub.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.

## Acknowledgments

- Inspired by XML serialization needs in complex PHP applications.
- Thanks to the contributors for making this package possible.

