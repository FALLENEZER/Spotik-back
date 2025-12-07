<?php

namespace App\Service;

use App\DTO\Input\Room\RoomCreateInputDTO;
use App\Entity\Room;
use App\Entity\User;
use App\Factory\RoomFactory;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomService
{
    public function __construct(
        private RoomRepository         $roomRepository,
        private EntityManagerInterface $em,
        private RoomPublisher         $roomPublisher,
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
            throw new RuntimeException('User already joined');
        }

        if (count($room->getMembers()) >= $room->getMaxUsers()) {
            throw new RuntimeException('Room is full');
        }

        $room->addMember($user);
        $this->em->persist($room);
        $this->em->flush();

        $this->roomPublisher->publish($room, 'user_joined', [
            'username' => $user->getName()
        ]);

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

    public function destroy(Room $room): JsonResponse
    {
        $this->roomRepository->destroy($room);
    }
}
