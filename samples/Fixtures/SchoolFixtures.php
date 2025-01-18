<?php

namespace Samples\Fixtures;

use Samples\Dto\School;

class SchoolFixtures
{

    public static function createSchool(): School
    {
        $school = new School();
        $school->setName("Ensi tanger");
        $teachers = TeacherFixtures::createTeachers();
        $rooms = RoomFixtures::createRooms();
        $adresse = AdresseFixtures::createAdresses();

        $school->setAdresse($adresse);
        $school->setTeachers($teachers);
        $school->setRooms($rooms);
        return $school;
    }

    public static function createSchoolArray(): array
    {
        return  [
            'NAME' => 'Ensi tanger',
            'ADRESSE' => [
                '@attributes' => [
                    'ID' => 100
                ],
                'adresse-city' => 'Tangier',
                'STREET' => 'Casabarata',
                'COUNTRY' => 'Morocco',
                'ZIP' => 90090
            ],
            'TEACHERS' => [
                'TEACHER' => [
                    [
                        'NAME' => 'John Doe',
                        'AGE' => 30,
                        'GENDER' => 'M'
                    ],
                    [
                        'NAME' => 'jaclyne Doe',
                        'AGE' => 30,
                        'GENDER' => 'F'
                    ]
                ]
            ],
            'school-rooms' => [
                'ROOM' => [
                    [
                        'NUMBER' => 'R1',
                        'DEPARTEMENTID' => 'D1'
                    ],
                    [
                        'NUMBER' => 'R2',
                        'DEPARTEMENTID' => 'D2'
                    ]
                ]
            ]
        ];
    }

    public static function createSchoolXml(): string
    {
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
        return $xmlString;

    }
}