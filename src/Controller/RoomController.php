<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use App\Factory\RoomFactory;
use App\Repository\RoomRepository;
use App\Repository\UserRepository;
use App\ResponseBuilder\RoomResponseBuilder;
use App\Service\RoomService;
use App\Service\RoomStateService;
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
        private RoomStateService             $roomStateService,
        private UserRepository               $userRepository,
    )
    {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $rooms = $this->roomService->index();
        return $this->builder->indexRoomResponse($rooms);
    }


//    #[Route('/api/rooms', name: 'rooms_create', methods: ['GET'])]
//    public function create(Request $request): JsonResponse
//    {
//        $data = json_decode($request->getContent(), true);
//
//        $host = $this->getUser();
//        if (!$host instanceof User) {
//            return $this->json(['error' => 'Unauthorized'], 401);
//        }
//
//        $roomCreateInputDTO = $this->roomFactory->makeRoomCreateInputDTO($data);
//    }
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

    #[Route('/{room<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Room $room): JsonResponse
    {
        return $this->builder->showRoomResponse($room);
    }

    #[Route('/{room<\d+>}/join', name: 'join', methods: ['POST'])]
    public function join(Room $room): JsonResponse
    {
         $user = $this->getUser();

         if (!$user instanceof User) {
             return  $this->json(['error' => 'Unauthorized'], 401);
         }

        try {
            $updatedRoom = $this->roomService->joinRoom($room, $user);
        } catch (\RuntimeException $exception) {
             return  $this->json(['error' => $exception->getMessage()], 400);
        }

         return $this->builder->joinRoomResponse($updatedRoom);
    }

    #[Route('/{room<\d+>}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(Room $room): JsonResponse
    {
        $this->roomService->destroy($room);
        return $this->builder->destroyRoomResponse();
    }

    // --- SLUG-BASED ENDPOINTS (temporary, for string IDs like "default") ---

    #[Route('/{id<[a-zA-Z][a-zA-Z0-9_-]*>}', name: 'show_slug', methods: ['GET'])]
    public function showBySlug(string $id): JsonResponse
    {
        $room = $this->roomStateService->getRoom($id);
        return new JsonResponse($room);
    }

    #[Route('/{id<[a-zA-Z][a-zA-Z0-9_-]*>}/join', name: 'join_slug', methods: ['POST'])]
    public function joinBySlug(Request $request, string $id): JsonResponse
    {
        // Временная авторизация: email из Authorization: Bearer <email> или ?email=
        $email = $request->query->get('email');
        if (!$email) {
            $authorization = $request->headers->get('Authorization');
            if ($authorization && str_starts_with($authorization, 'Bearer ')) {
                $email = substr($authorization, 7);
            }
        }

        if (!$email) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return $this->json(['message' => 'Invalid credentials'], 401);
        }

        $participant = [
            'id' => $user->getId(),
            'name' => method_exists($user, 'getName') ? $user->getName() : ($user->getEmail() ?? 'User'),
        ];

        $room = $this->roomStateService->ensureParticipant($id, $participant);

        return new JsonResponse($room);
    }
}

