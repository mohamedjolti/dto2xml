<?php

namespace Samples\Fixtures;

use Samples\Dto\Room;

class RoomFixtures
{

    public static function createRooms(): array
    {
        $room1 = new Room();
        $room1->setNumber("R1");
        $room1->setDepartementId("D1");

        $room2 = new Room();
        $room2->setNumber("R2");
        $room2->setDepartementId("D2");
        return [$room1, $room2];
    }
}