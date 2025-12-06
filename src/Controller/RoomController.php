<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use App\Factory\RoomFactory;
use App\Repository\RoomRepository;
use App\ResponseBuilder\RoomResponseBuilder;
use App\Service\RoomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rooms', name: 'rooms_')]
class RoomController extends AbstractController
{
    public function __construct(
        private RoomService                  $roomService,
        private RoomFactory                  $roomFactory,
        private readonly RoomResponseBuilder $builder,
    )
    {
    }

    #[Route('api/rooms', name: 'rooms_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $rooms = $this->roomService->index();
        return $this->builder->indexRoomResponse($rooms);
    }


    #[Route('/api/rooms', name: 'rooms_create', methods: ['GET'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $host = $this->getUser();
        if (!$host instanceof User) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $roomCreateInputDTO = $this->roomFactory->makeRoomCreateInputDTO($data);
    }
////
//    #[Route('', name: 'create', methods: ['POST'])]
//    public function create(Request $request): JsonResponse
//    {
//        try {
//            $payload = $this->decode($request);
//        } catch (\InvalidArgumentException $exception) {
//            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
//        }
//        $host = $this->requestUserProvider->resolve($request);
//
//        $room = new Room();
//        $room
//            ->setName($payload['name'] ?? 'Spotik Room')
//            ->setMaxUsers(isset($payload['maxUsers']) ? (int) $payload['maxUsers'] : 50)
//            ->setIsPrivate((bool) ($payload['isPrivate'] ?? false))
//            ->setHost($host);
//
//        $room->addMember($host);
//
//        $this->entityManager->persist($room);
//        $this->entityManager->flush();
//
//        return $this->json($this->transformRoom($room, true), Response::HTTP_CREATED);
//    }

    #[Route('api/rooms/{room}', name: 'rooms_show', methods: ['GET'])]
    public function show(Room $room): JsonResponse
    {
        return $this->builder->showRoomResponse($room);
    }

    #[Route('api/rooms/{room}', name: 'rooms_join', methods: ['POST'])]
    public function join(Request $request): JsonResponse
    {

    }
}

