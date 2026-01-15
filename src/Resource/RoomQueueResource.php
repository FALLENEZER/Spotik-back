<?php

namespace App\Resource;

use App\DTO\Output\RoomQueue\RoomQueueOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class RoomQueueResource
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function roomQueueItem(RoomQueueOutputDTO $dto): string
    {
        return $this->serializer->serialize($dto, 'json');
    }
}
