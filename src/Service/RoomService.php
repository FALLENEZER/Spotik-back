<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\User;
use App\Factory\RoomFactory;
use App\Repository\RoomRepository;
use http\Exception\RuntimeException;

class RoomService
{
    public function __construct(
        private readonly RoomRepository $repository,
        private readonly RoomFactory    $factory,
        private readonly RoomPublisher  $hub,
    )
    {

    }

    public function index(): array
    {
        return $this->repository->findAll();
    }

    public function create(User $user, array $data): Room
    {
        $roomDto = $this->factory->makeRoomCreateInputDTO($data);
        $roomDto->host = $user;
        $room = $this->factory->makeRoom($roomDto);

        return $this->repository->create($room);
    }

    public function joinRoom(Room $room, User $user): Room
    {
        if ($room->getMembers()->contains($user)) {
            throw new RuntimeException('User already joined');
        }

        if (count($room->getMembers()) >= $room->getMaxUsers()) {
            throw new RuntimeException('Room is full');
        }

        $room = $this->repository->join($room, $user);

        $this->hub->publish($room, 'user_joined', [
            'username' => $user->getName()
        ]);

        return $room;
    }

    public function leaveRoom(Room $room, User $user): Room
    {
        if (!$room->getMembers()->contains($user)) {
            throw new RuntimeException('User not in room');
        }

        $room = $this->repository->leave($room, $user);
        $this->hub->publish($room, 'user_left', [
            'username' => $user->getName()
        ]);

        return $room;
    }

    public function delete(Room $room): void
    {
        $this->repository->delete($room);
    }
}
