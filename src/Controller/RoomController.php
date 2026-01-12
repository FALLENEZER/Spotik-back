<?php

namespace App\Controller;

use App\Entity\Room;
use App\ResponseBuilder\RoomResponseBuilder;
use App\Service\RoomService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rooms', name: 'rooms_')]
class RoomController extends AppController
{
    public function __construct(
        private readonly RoomService         $service,
        private readonly RoomResponseBuilder $responseBuilder,
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $rooms = $this->service->index();
        return $this->responseBuilder->indexRoomResponse($rooms);
    }


    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $room = $this->service->create($this->getUser(), $data);

        return $this->responseBuilder->createRoomResponse($room);
    }

    #[Route('/{room<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Room $room): JsonResponse
    {
        return $this->responseBuilder->showRoomResponse($room);
    }

    #[Route('/{room<\d+>}/join', name: 'join', methods: ['POST'])]
    public function join(Room $room): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $this->service->joinRoom($room, $user);

        return $this->responseBuilder->joinRoomResponse($room);
    }

    #[Route('/{room<\d+>}/leave', name: 'leave', methods: ['POST'])]
    public function leave(Room $room): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $this->service->leaveRoom($room, $user);

        return $this->responseBuilder->leaveRoomResponse($room);
    }

    #[Route('/rooms/{room<\d+>}', name: 'destroy', methods: ['DELETE'])]
    public function delete(Room $room): JsonResponse
    {
        $user = $this->getUser();
        if ($room->getHost() === $user) {
            $this->service->delete($room);
            return $this->responseBuilder->destroyRoomResponse();
        }

        return $this->json(['error' => 'Not allowed'], 401);
    }
}

