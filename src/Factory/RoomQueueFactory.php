<?php

namespace App\Factory;

use App\DTO\Input\RoomQueue\RoomQueueInputDTO;
use App\DTO\Output\RoomQueue\RoomQueueOutputDTO;
use App\Entity\Room;
use App\Entity\RoomQueue;
use App\Entity\Track;
use App\Entity\User;

class RoomQueueFactory
{
    public function __construct(
        private readonly RoomFactory $roomFactory,
        private readonly TrackFactory $trackFactory,
        private readonly UserFactory $userFactory,
    )
    {

    }


    public function makeRoomQueueItem(Room $room, Track $track, User $user): RoomQueue
    {
        $queueItem = new RoomQueue();
        $queueItem->setRoom($room);
        $queueItem->setTrack($track);
        $queueItem->setAddedBy($user);
        $queueItem->setAddedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
        $queueItem->setStatus('pending');
        $queueItem->setScore(0);
        $queueItem->setPriority(0);

        return $queueItem;
    }

    public function makeRoomQueueInputDTO(array $data): RoomQueueInputDTO
    {
        $dto = new RoomQueueInputDTO();
        $dto->trackId = $data['trackId'] ?? null;
        $dto->playlistId = $data['playlistId'] ?? null;
        return $dto;
    }

    public function makeRoomQueueOutputDTO(RoomQueue $queueItem): RoomQueueOutputDTO
    {
        $dto = new RoomQueueOutputDTO();
        $dto->room = $this->roomFactory->makeRoomOutputDTO($queueItem->getRoom());
        $dto->track = $this->trackFactory->makeTrackOutputDTO($queueItem->getTrack());
        $dto->addedBy = $this->userFactory->makeUserOutputDTO($queueItem->getAddedBy());
        return $dto;
    }
}
