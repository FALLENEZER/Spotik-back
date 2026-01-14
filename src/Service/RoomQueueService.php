<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\User;

class RoomQueueService
{
    function __construct(private readonly RoomPublisher $hub)
    {

    }

    public function togglePLayback(Room $room, User $user, string $action): void
    {
        $this->hub->publish($room, 'playback_control', [
            'action' => $action,
            'userId' => $user->getId(),
            'username' => $user->getName(),
        ]);
    }

    public function sendWebRTCSignal(Room $room, User $sender, array $payload)
    {

    }
}
