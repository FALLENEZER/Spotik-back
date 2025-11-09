<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    /**
     * @var Collection<int, Track>
     */
    #[ORM\ManyToMany(targetEntity: Track::class, inversedBy: 'playlists')]
    private Collection $playlistTrack;

    /**
     * @var Collection<int, RoomQueue>
     */
    #[ORM\OneToMany(targetEntity: RoomQueue::class, mappedBy: 'playlist')]
    private Collection $roomQueues;

    public function __construct()
    {
        $this->playlistTrack = new ArrayCollection();
        $this->roomQueues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return Collection<int, Track>
     */
    public function getPlaylistTrack(): Collection
    {
        return $this->playlistTrack;
    }

    public function addPlaylistTrack(Track $playlistTrack): static
    {
        if (!$this->playlistTrack->contains($playlistTrack)) {
            $this->playlistTrack->add($playlistTrack);
        }

        return $this;
    }

    public function removePlaylistTrack(Track $playlistTrack): static
    {
        $this->playlistTrack->removeElement($playlistTrack);

        return $this;
    }

    /**
     * @return Collection<int, RoomQueue>
     */
    public function getRoomQueues(): Collection
    {
        return $this->roomQueues;
    }

    public function addRoomQueue(RoomQueue $roomQueue): static
    {
        if (!$this->roomQueues->contains($roomQueue)) {
            $this->roomQueues->add($roomQueue);
            $roomQueue->setPlaylist($this);
        }

        return $this;
    }

    public function removeRoomQueue(RoomQueue $roomQueue): static
    {
        if ($this->roomQueues->removeElement($roomQueue)) {
            // set the owning side to null (unless already changed)
            if ($roomQueue->getPlaylist() === $this) {
                $roomQueue->setPlaylist(null);
            }
        }

        return $this;
    }
}
