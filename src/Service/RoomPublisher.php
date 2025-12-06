<?php

namespace App\Service;

use App\Entity\Room;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class RoomPublisher
{
    public function __construct(private HubInterface $hub)
    {

    }
    public function publish(Room $room, string $event, array $payload = []): void
    {
        $data = array_merge(['event' => $event, 'roomId' => $room->getId()], $payload);
        $update = new Update(
            topics: sprintf('rooms/%d', $room->getId()),
            data: json_encode($data),
        );
        $this->hub->publish($update);
    }
}
