<?php

namespace App\Entity;

use App\Repository\RoomQueueVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomQueueVoteRepository::class)]
#[ORM\Table(name: 'room_queue_vote', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'uniq_vote_queue_user', columns: ['queue_item_id', 'user_id']),
])]
class RoomQueueVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RoomQueue $queueItem = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private int $value = 0;

    #[ORM\Column]
    private \DateTimeImmutable $votedAt;

    public function __construct()
    {
        $this->votedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQueueItem(): ?RoomQueue
    {
        return $this->queueItem;
    }

    public function setQueueItem(?RoomQueue $queueItem): static
    {
        $this->queueItem = $queueItem;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getVotedAt(): \DateTimeImmutable
    {
        return $this->votedAt;
    }

    public function setVotedAt(\DateTimeImmutable $votedAt): static
    {
        $this->votedAt = $votedAt;

        return $this;
    }
}

