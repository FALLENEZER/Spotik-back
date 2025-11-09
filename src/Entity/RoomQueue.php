<?php

namespace App\Entity;

use App\Repository\RoomQueueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomQueueRepository::class)]
class RoomQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'roomQueues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Track $track = null;

    #[ORM\ManyToOne(inversedBy: 'roomQueues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Playlist $playlist = null;

    #[ORM\Column]
    private ?int $priority = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPlaylist(): ?Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }
}
