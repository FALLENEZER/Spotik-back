<?php

namespace App\Service;

use App\Entity\Room;
use App\Entity\RoomQueue;
use App\Entity\RoomQueueVote;
use App\Entity\Track;
use App\Entity\User;
use App\Repository\RoomQueueRepository;
use App\Repository\RoomQueueVoteRepository;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;

class RoomQueueManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TrackRepository $trackRepository,
        private readonly RoomQueueRepository $queueRepository,
        private readonly RoomQueueVoteRepository $voteRepository,
        private readonly MusicCatalogService $musicCatalogService,
    ) {
    }

    public function enqueue(Room $room, string $externalTrackId, User $user): RoomQueue
    {
        $track = $this->trackRepository->findOneBy([
            'spotifyId' => $externalTrackId,
        ]);

        if (!$track) {
            $trackData = $this->musicCatalogService->getTrack($externalTrackId);
            $track = $this->createTrackFromCatalog($trackData);
        }

        $priority = $this->queueRepository->getNextPriorityForRoom($room);

        $queueItem = new RoomQueue();
        $queueItem
            ->setRoom($room)
            ->setTrack($track)
            ->setAddedBy($user)
            ->setPriority($priority);

        $this->entityManager->persist($queueItem);
        $this->entityManager->flush();

        return $queueItem;
    }

    public function remove(RoomQueue $queueItem): void
    {
        $this->entityManager->remove($queueItem);
        $this->entityManager->flush();
    }

    public function vote(RoomQueue $queueItem, User $user, int $value): RoomQueue
    {
        if (!\in_array($value, [-1, 0, 1], true)) {
            throw new \InvalidArgumentException('Vote value must be -1, 0 or 1');
        }

        $existing = $this->voteRepository->findOneByQueueItemAndUser($queueItem, $user);

        if ($value === 0) {
            if ($existing) {
                $queueItem->incrementScore(-$existing->getValue());
                $this->entityManager->remove($existing);
            }
        } elseif ($existing) {
            $delta = $value - $existing->getValue();
            if ($delta !== 0) {
                $queueItem->incrementScore($delta);
            }
            $existing->setValue($value)->setVotedAt(new \DateTimeImmutable());
        } else {
            $vote = new RoomQueueVote();
            $vote
                ->setQueueItem($queueItem)
                ->setUser($user)
                ->setValue($value);
            $queueItem->incrementScore($value);
            $this->entityManager->persist($vote);
        }

        $this->entityManager->flush();

        return $queueItem;
    }

    /**
     * @param array<string, mixed> $trackData
     */
    private function createTrackFromCatalog(array $trackData): Track
    {
        if (!isset($trackData['trackId'])) {
            throw new \RuntimeException('Catalog track data missing trackId');
        }

        $track = new Track();
        $track
            ->setSpotifyId((string) $trackData['trackId'])
            ->setName($trackData['trackName'] ?? 'Untitled track')
            ->setArtist($trackData['artistName'] ?? null)
            ->setImageUrl($trackData['artworkUrl100'] ?? null)
            ->setDuration(isset($trackData['trackTimeMillis']) ? (int) $trackData['trackTimeMillis'] : null);

        if (!empty($trackData['releaseDate'])) {
            try {
                $track->setReleaseDate(new \DateTimeImmutable((string) $trackData['releaseDate']));
            } catch (\Exception) {
                // ignore parse errors
            }
        }

        $this->entityManager->persist($track);
        $this->entityManager->flush();

        return $track;
    }
}

