<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $spotifyId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $publishedAt = null;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\ManyToMany(targetEntity: Playlist::class, mappedBy: 'playlistTrack')]
    private Collection $playlists;

    /**
     * @var Collection<int, RoomQueue>
     */
    #[ORM\OneToMany(targetEntity: RoomQueue::class, mappedBy: 'track')]
    private Collection $roomQueues;

    public function __construct()
    {
        $this->playlists = new ArrayCollection();
        $this->roomQueues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpotifyId(): ?int
    {
        return $this->spotifyId;
    }

    public function setSpotifyId(int $spotifyId): static
    {
        $this->spotifyId = $spotifyId;

        return $this;
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

    public function getArtist(): ?string
    {
        return $this->artist;
    }

    public function setArtist(?string $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

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

    /**
     * @return Collection<int, Playlist>
     */
    public function getPlaylists(): Collection
    {
        return $this->playlists;
    }

    public function addPlaylist(Playlist $playlist): static
    {
        if (!$this->playlists->contains($playlist)) {
            $this->playlists->add($playlist);
            $playlist->addPlaylistTrack($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            $playlist->removePlaylistTrack($this);
        }

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
            $roomQueue->setTrack($this);
        }

        return $this;
    }

    public function removeRoomQueue(RoomQueue $roomQueue): static
    {
        if ($this->roomQueues->removeElement($roomQueue)) {
            // set the owning side to null (unless already changed)
            if ($roomQueue->getTrack() === $this) {
                $roomQueue->setTrack(null);
            }
        }

        return $this;
    }
}
