<?php

namespace App\Factory;

use App\DTO\Output\RoomQueue\RoomQueueShortOutputDTO;
use App\Entity\RoomQueue;

class RoomQueueFactory
{


    public function makeRoomQueueShortOutputDTO(RoomQueue $roomQueue): RoomQueueShortOutputDTO
    {
        $roomQueueDto = new RoomQueueShortOutputDTO();

//        $roomQueueDto
    }
}
