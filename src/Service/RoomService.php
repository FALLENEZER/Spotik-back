<?php

namespace App\Service;

use App\DTO\Input\Room\RoomCreateInputDTO;
use App\Entity\Room;
use App\Entity\User;
use App\Factory\RoomFactory;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomService
{
    public function __construct(
        private RoomRepository $roomRepository,
        private EntityManagerInterface $em,
    )
    {

    }

    public function index(): array
    {
        return $this->roomRepository->findAll();
    }

    public function create(User $host, RoomCreateInputDTO $inputDTO): Room
    {
        $room = new Room();


    }
    public function joinRoom(Room $room, User $user): Room
    {
        if ($room->getMembers()->contains($user)) {
            $room->addMember($user);
            $this->em->flush();
        }

        return $room;
    }

    public function leaveRoom(Room $room, User $user): Room
    {
        if ($room->getMembers()->contains($user)) {
            $room->removeMember($user);
            $this->em->flush();
        }
        return $room;
    }
}
