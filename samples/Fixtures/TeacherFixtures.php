<?php

namespace Samples\Fixtures;

use Samples\Dto\Teacher;

class TeacherFixtures
{
    /**
     * @return array<Teacher>
     */
    public static function createTeachers(): array
    {
        /* Teachers */
        $teacher1  = new Teacher();
        $teacher1->setName("John Doe");
        $teacher1->setAge("30");
        $teacher1->setGender("M");

        $teacher2  = new Teacher();
        $teacher2->setName("jaclyne Doe");
        $teacher2->setAge("30");
        $teacher2->setGender("F");
        return [
          $teacher1,
          $teacher2
        ];
    }

}