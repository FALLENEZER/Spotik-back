<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use App\Repository\RoomRepository;
use App\Service\RequestUserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/rooms', name: 'rooms_')]
class RoomController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestUserProvider $requestUserProvider,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(RoomRepository $roomRepository): JsonResponse
    {
        $rooms = array_map(
            fn (Room $room) => $this->transformRoom($room, false),
            $roomRepository->findAll()
        );

        return $this->json([
            'items' => $rooms,
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $payload = $this->decode($request);
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $host = $this->requestUserProvider->resolve($request);

        $room = new Room();
        $room
            ->setName($payload['name'] ?? 'Spotik Room')
            ->setMaxUsers(isset($payload['maxUsers']) ? (int) $payload['maxUsers'] : 50)
            ->setIsPrivate((bool) ($payload['isPrivate'] ?? false))
            ->setHost($host);

        $room->addMember($host);

        $this->entityManager->persist($room);
        $this->entityManager->flush();

        return $this->json($this->transformRoom($room, true), Response::HTTP_CREATED);
    }

    #[Route('/{room}', name: 'show', methods: ['GET'])]
    public function show(Room $room): JsonResponse
    {
        return $this->json($this->transformRoom($room, true));
    }

    /**
     * @return array<string, mixed>
     */
    private function transformRoom(Room $room, bool $withMembers): array
    {
        $data = [
            'id' => $room->getId(),
            'name' => $room->getName(),
            'isPrivate' => $room->isPrivate(),
            'maxUsers' => $room->getMaxUsers(),
            'createdAt' => $room->getCreatedAt()?->format(DATE_ATOM),
            'host' => $this->transformUser($room->getHost()),
            'queueSize' => $room->getQueueItems()->count(),
        ];

        if ($withMembers) {
            $data['members'] = array_map(
                fn (User $user) => $this->transformUser($user),
                $room->getMembers()->toArray()
            );
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function transformUser(?User $user): array
    {
        if (!$user instanceof User) {
            return [];
        }

        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function decode(Request $request): array
    {
        $content = (string) $request->getContent();
        if ($content === '') {
            return [];
        }

        $data = json_decode($content, true);

        if (!\is_array($data)) {
            throw new \InvalidArgumentException('Invalid JSON payload');
        }

        return $data;
    }
}

