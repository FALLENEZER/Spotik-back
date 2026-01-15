<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\RoomQueue;
use App\Entity\User;
use App\Factory\RoomQueueFactory;
use App\Repository\RoomRepository;
use App\Repository\TrackRepository;

class RoomQueueService
{
    function __construct(
        private readonly TrackRepository $trackRepository,
        private readonly RoomQueueFactory $queueFactory,
        private readonly RoomPublisher $hub)
    {

    }

    public function addTrack(Room $room, User $user, array $data): RoomQueue
    {
        $dto =  $this->queueFactory->makeRoomQueueInputDTO($data);

        $track = $this->trackRepository->find($dto->trackId);
        if (!$track) {
            throw new \Exception('Track not found');
        }

        $queueItem = $this->queueFactory->makeRoomQueueItem($room, $track, $user);

        $this->hub->publish($room, 'track_added', [
            'queueItem' => [
                'id' => $queueItem->getId(),
                'trackId' => $track->getId(),
                'title' => $track->getName(),
                'artist' => $track->getArtist(),
                'duration' => $track->getDuration(),
                'addedBy' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                ],
                'addedAt' => $queueItem->getAddedAt()->format(DATE_ATOM),
            ],
        ]);
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

    public function vote(Room $room, RoomQueue $queueItem, User $user): void
    {

    }
}
