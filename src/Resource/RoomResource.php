<?php

namespace App\Resource;

use App\DTO\Output\Room\RoomOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class RoomResource
{
    function __construct(private  SerializerInterface $serializer)
    {
    }

    public function roomItem(RoomOutputDTO $room): string
    {
        return $this->serializer->serialize($room, 'json');
    }

    public function roomCollection(array $rooms): string
    {
        return $this->serializer->serialize($rooms, 'json');
    }
}
