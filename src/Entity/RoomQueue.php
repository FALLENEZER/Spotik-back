<?php

namespace App\Entity;

use App\Repository\RoomQueueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomQueueRepository::class)]
class RoomQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'queueItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $room = null;

    #[ORM\ManyToOne(inversedBy: 'roomQueues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Track $track = null;

    #[ORM\ManyToOne]
    private ?User $addedBy = null;

    #[ORM\ManyToOne(inversedBy: 'roomQueues')]
    private ?Playlist $playlist = null;

    #[ORM\Column(options: ['default' => 0])]
    private int $priority = 0;

    #[ORM\Column(options: ['default' => 0])]
    private int $score = 0;

    #[ORM\Column(length: 32, options: ['default' => 'pending'])]
    private string $status = 'pending';

    #[ORM\Column]
    private \DateTimeImmutable $addedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $finishedAt = null;

    /**
     * @var Collection<int, RoomQueueVote>
     */
    #[ORM\OneToMany(mappedBy: 'queueItem', targetEntity: RoomQueueVote::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $votes;

    public function __construct()
    {
        $this->addedAt = new \DateTimeImmutable();
        $this->votes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    public function getTrack(): ?Track
    {
        return $this->track;
    }

    public function setTrack(?Track $track): static
    {
        $this->track = $track;

        return $this;
    }

    public function getAddedBy(): ?User
    {
        return $this->addedBy;
    }

    public function setAddedBy(?User $addedBy): static
    {
        $this->addedBy = $addedBy;

        return $this;
    }

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function incrementScore(int $delta): static
    {
        $this->score += $delta;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(?\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeImmutable
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeImmutable $finishedAt): static
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    /**
     * @return Collection<int, RoomQueueVote>
     */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(RoomQueueVote $vote): static
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setQueueItem($this);
        }

        return $this;
    }

    public function removeVote(RoomQueueVote $vote): static
    {
        if ($this->votes->removeElement($vote)) {
            if ($vote->getQueueItem() === $this) {
                $vote->setQueueItem(null);
            }
        }

        return $this;
    }
}
