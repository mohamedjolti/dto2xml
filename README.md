# XML Serializer Package

This package provides a simple and efficient way to serialize DTOs (Data Transfer Objects) with subtypes to XML and deserialize XML back to PHP objects.

## What is `dto2xml`?
 
At its core, `dto2xml` is a PHP library that lets you:

- Serialize objects (including those with subtypes) into well-structured XML documents.
- Unserialize XML back into PHP objects effortlessly.

The library is built with flexibility and ease of use in mind, making it ideal for both small-scale and enterprise-level applications.

---

## Why `dto2xml`?

Here are some reasons to consider `dto2xml` for your next project:

1. **Handles Subtypes Gracefully**: Many libraries struggle when dealing with objects containing subtypes or nested structures. `dto2xml` shines in this area.
2. **Configurable Output**: You can customize XML headers, namespaces, and more to match your specific requirements.
3. **Clean and Intuitive API**: With a few lines of code, you can transform objects into XML or parse XML back into objects.

---

## Installation

To get started with `dto2xml`, simply install it via Composer:

```bash
composer require jolti/dto2xml
```

---

## How to Use `dto2xml`

### Setting Up the Serializer

Let’s begin by setting up the serializer with a custom configuration:

```php
use Dtotoxml\XmlSerializer;
use Dtotoxml\Configuration;
use Samples\Dto\School;
use Samples\Dto\SchoolFixtures;

$xmlSerialize = new XmlSerializer();

// Configure the serializer
$config = new Configuration();
$config->setHead('<?xml version="1.0"?>');
$config->setNameSpaceOutput("Samples\\Dto");
$xmlSerialize->setConfig($config);
```

### Serializing an Object to XML

To serialize an object, such as a `School` object with nested attributes, follow this approach:

```php
$school = SchoolFixtures::createSchool();
$xml = $xmlSerialize->format($xmlSerialize->serialise($school));

// Output the XML
echo '<pre>', htmlentities($xml), '</pre>';
```

### Deserializing XML to an Object

Converting XML back into a PHP object is just as easy:

```php
$xmlString = SchoolFixtures::createSchoolXml();
$object = $xmlSerialize->unserialise($xmlString, School::class);

// Access the deserialized object
var_dump($object);
```

### Example DTO: School

Here’s a sample Data Transfer Object (DTO) for a school, complete with subtypes:

```php
namespace Samples\Dto;

class School
{
    private $name;
    private $adresse;
    private $teachers;
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

---

## Annotations for Serialization and Deserialization

The `dto2xml` library uses annotations to guide the serialization and deserialization process, providing powerful customization options. Here are the key annotations:

### **`@isAttribute`**
- **Purpose**: Marks a property as an XML attribute instead of an XML element.
- **Example**:
  ```php
  /**
   * @isAttribute
   * @var string $id
   */
  private string $id;
  ```
    - The `id` field will be serialized as an attribute of the parent XML element, not as a child element.
    - **XML Example**:
      ```xml
      <Adresse id="100">
          <!-- Other elements -->
      </Adresse>
      ```
    - This is useful for compact XML representations where certain values are better represented as attributes.

---

### **`@outputName`**
- **Purpose**: Specifies the name of the field when serializing the object into XML.
- **Example**:
  ```php
  /**
   * @outputName adresse-city
   * @inputName adresse-city
   * @var string $city
   */
  private $city;
  ```
    - The `city` property will appear as `<adresse-city>` in the XML output.
    - **XML Example**:
      ```xml
      <Adresse>
          <adresse-city>Tangier</adresse-city>
      </Adresse>
      ```
    - This allows you to control the naming conventions in your XML output to match specific schemas or external requirements.

---

### **`@inputName`**
- **Purpose**: Specifies the name of the field when deserializing XML back into a PHP object.
- **Example**:
  ```php
  /**
   * @outputName adresse-city
   * @inputName adresse-city
   * @var string $city
   */
  private $city;
  ```
    - During deserialization, the library will map the `<adresse-city>` XML element back to the `city` property in the `Adresse` object.
    - **XML Example**:
      ```xml
      <Adresse>
          <adresse-city>Tangier</adresse-city>
      </Adresse>
      ```
    - This ensures that even if the XML uses custom or non-standard names, the library can correctly map them to the appropriate object properties.

---

### Practical Usage
These annotations are critical for ensuring flexibility in how the `dto2xml` library handles XML. They allow you to:
1. **Customize XML Output**:
    - Use `@outputName` to control the tag names for fields, ensuring compatibility with external systems.
2. **Handle Non-Standard XML Inputs**:
    - Use `@inputName` to map non-standard XML names to your PHP properties.
3. **Compact Representations**:
    - Use `@isAttribute` to define compact XML structures by leveraging attributes instead of child elements.

---

## Configuration Options

The `Configuration` class allows you to customize the serialization process to fit your needs. For example:

- **XML Header**: Set a custom XML declaration using `$config->setHead()`.
- **Namespace**: Define the namespace for your XML output with `$config->setNameSpaceOutput()`.

---

## Why Developers Love `dto2xml`

1. **Ease of Use**: The library’s API is straightforward and developer-friendly.
2. **Powerful Features**: It handles nested objects and subtypes with ease.
3. **Customizability**: You can tweak the output XML to meet any specification.

---

## Get Started Today!

Ready to simplify your XML serialization tasks? Download `dto2xml` today and experience its power firsthand.

Feel free to contribute to the project, report issues, or suggest features. Let’s make XML handling in PHP simpler, together.

---

## Stay Connected

Follow us for updates and tips on getting the most out of `dto2xml`. Happy coding!

