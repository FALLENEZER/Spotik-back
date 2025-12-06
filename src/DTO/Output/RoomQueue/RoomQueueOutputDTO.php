<?php

namespace App\DTO\Output\RoomQueue;

use App\Entity\Playlist;
use App\Entity\Room;
use App\Entity\RoomQueueVote;
use App\Entity\Track;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

class RoomQueueOutputDTO
{
    private ?Room $room = null;
    private ?Track $track = null;

    private ?User $addedBy = null;

    private ?Playlist $playlist = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $priority = 0;

    private int $score = 0;

    private string $status = 'pending';
    private \DateTimeImmutable $addedAt;
    private ?\DateTimeImmutable $startedAt = null;

    private ?\DateTimeImmutable $finishedAt = null;

    /**
     * @var Collection<int, RoomQueueVote>
     */
    private Collection $votes;
}
