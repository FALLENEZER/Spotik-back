<?php

namespace App\Factory;

use App\DTO\Input\Room\RoomCreateInputDTO;
use App\DTO\Input\Room\RoomInputDTO;
use App\DTO\Output\Room\RoomOutputDTO;
use App\Entity\Room;

class RoomFactory
{
    public function __construct(
        private UserFactory $userFactory,
    )
    {

    }

    public function makeRoom(RoomCreateInputDTO $roomInputDTO): Room
    {
        $room = new Room();
        $room->setName($roomInputDTO->name);
        $room->setHost($roomInputDTO->host);
        $room->addMember($roomInputDTO->host);
        $room->setMaxUsers($roomInputDTO->maxUsers);
        $room->setIsPrivate($roomInputDTO->isPrivate);
        return $room;
    }

    public function makeRoomCreateInputDTO(array $data): RoomCreateInputDTO
    {
        $room = new RoomCreateInputDTO();

        $room->name = $data['name'] ?? null;
        $room->maxUsers = isset($data['maxUsers'])
            ? (int)$data['maxUsers']
            : $room->maxUsers;
        $room->isPrivate = isset($data['isPrivate'])
            ? (bool)$data['isPrivate']
            : $room->isPrivate;

        return $room;
    }

    public function makeRoomOutputDTOs(array $rooms): array
    {
        return array_map(fn(Room $room) => $this->makeRoomOutputDTO($room), $rooms);
    }

    public function makeRoomOutputDTO(Room $room): RoomOutputDTO
    {
        $roomDto = new RoomOutputDTO();

        $roomDto->name = $room->getName();
        $roomDto->createdAt = $room->getCreatedAt();
        $roomDto->maxUsers = $room->getMaxUsers();
        $roomDto->isPrivate = $room->isPrivate();
        $roomDto->host = $this->userFactory->makeUserShortOutputDTO($room->getHost());
        $roomDto->members = $this->userFactory->makeUserShortOutputDTOs($room->getMembers());

        return $roomDto;
    }

}
