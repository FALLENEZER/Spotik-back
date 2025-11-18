<?php

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Room;
use App\Entity\RoomQueue;
use App\Entity\User;
use App\Service\RequestUserProvider;
use App\Service\RoomQueueManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

#[Route('/api/rooms/{room}/queue', name: 'rooms_queue_')]
class RoomQueueController extends AbstractController
{
    public function __construct(
        private readonly RoomQueueManager $roomQueueManager,
        private readonly RequestUserProvider $requestUserProvider,
    ) {
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function index(Room $room, Request $request): JsonResponse
    {
        $currentUser = $this->requestUserProvider->resolveOptional($request);
        $items = array_map(
            fn (RoomQueue $queueItem) => $this->transformQueueItem($queueItem, $currentUser),
            $room->getQueueItems()->toArray()
        );

        return $this->json([
            'roomId' => $room->getId(),
            'items' => $items,
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Room $room, Request $request): JsonResponse
    {
        try {
            $payload = $this->decode($request);
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $trackId = $payload['trackId'] ?? null;

        if (!$trackId) {
            return $this->json(['error' => 'trackId is required'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->requestUserProvider->resolve($request);

        $queueItem = $this->roomQueueManager->enqueue($room, (string) $trackId, $user);

        return $this->json($this->transformQueueItem($queueItem, $user), Response::HTTP_CREATED);
    }

    #[Route('/{queueItem}', name: 'delete', methods: ['DELETE'])]
    public function delete(Room $room, RoomQueue $queueItem): JsonResponse
    {
        $this->guardRoomQueue($room, $queueItem);

        $this->roomQueueManager->remove($queueItem);

        return $this->json(['status' => 'removed']);
    }

    #[Route('/{queueItem}/vote', name: 'vote', methods: ['POST'])]
    public function vote(Room $room, RoomQueue $queueItem, Request $request): JsonResponse
    {
        $this->guardRoomQueue($room, $queueItem);

        try {
            $payload = $this->decode($request);
        } catch (\InvalidArgumentException $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $value = isset($payload['value']) ? (int) $payload['value'] : null;

        if (!\in_array($value, [-1, 0, 1], true)) {
            return $this->json(['error' => 'Vote value must be -1, 0 or 1'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->requestUserProvider->resolve($request);
        $updated = $this->roomQueueManager->vote($queueItem, $user, $value);

        return $this->json($this->transformQueueItem($updated, $user));
    }

    private function guardRoomQueue(Room $room, RoomQueue $queueItem): void
    {
        if ($queueItem->getRoom()?->getId() !== $room->getId()) {
            throw new ResourceNotFoundException('Queue item does not belong to this room');
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function transformQueueItem(RoomQueue $queueItem, ?User $currentUser): array
    {
        $track = $queueItem->getTrack();
        $votes = [
            'up' => 0,
            'down' => 0,
            'current' => 0,
        ];

        foreach ($queueItem->getVotes() as $vote) {
            $value = $vote->getValue();
            if ($value > 0) {
                ++$votes['up'];
            } elseif ($value < 0) {
                ++$votes['down'];
            }

            if ($currentUser instanceof User && $vote->getUser()?->getId() === $currentUser->getId()) {
                $votes['current'] = $value;
            }
        }

        return [
            'id' => $queueItem->getId(),
            'roomId' => $queueItem->getRoom()?->getId(),
            'priority' => $queueItem->getPriority(),
            'status' => $queueItem->getStatus(),
            'score' => $queueItem->getScore(),
            'addedAt' => $queueItem->getAddedAt()->format(DATE_ATOM),
            'addedBy' => $this->transformUser($queueItem->getAddedBy()),
            'playlist' => $this->transformPlaylist($queueItem->getPlaylist()),
            'track' => [
                'id' => $track?->getId(),
                'title' => $track?->getName(),
                'artist' => $track?->getArtist(),
                'artworkUrl' => $track?->getImageUrl(),
                'spotifyId' => $track?->getSpotifyId(),
                'releaseDate' => $track?->getReleaseDate()?->format(DATE_ATOM),
                'duration' => $track?->getDuration(),
            ],
            'votes' => $votes,
        ];
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
    private function transformPlaylist(?Playlist $playlist): array
    {
        if (!$playlist) {
            return [];
        }

        return [
            'id' => $playlist->getId(),
            'name' => $playlist->getName(),
            'createdAt' => $playlist->getCreatedAt()->format(DATE_ATOM),
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

        $payload = json_decode($content, true);

        if (!\is_array($payload)) {
            throw new \InvalidArgumentException('Invalid JSON payload');
        }

        return $payload;
    }
}

