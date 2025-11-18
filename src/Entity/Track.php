<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrackRepository::class)]
class Track
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128, unique: true, nullable: true)]
    private ?string $spotifyId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $artist = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $releaseDate = null;

    /**
     * @var Collection<int, PlaylistTrack>
     */
    #[ORM\OneToMany(mappedBy: 'track', targetEntity: PlaylistTrack::class, cascade: ['remove'])]
    private Collection $playlistTracks;

    /**
     * @var Collection<int, RoomQueue>
     */
    #[ORM\OneToMany(targetEntity: RoomQueue::class, mappedBy: 'track')]
    private Collection $roomQueues;

    public function __construct()
    {
        $this->playlistTracks = new ArrayCollection();
        $this->roomQueues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpotifyId(): ?string
    {
        return $this->spotifyId;
    }

    public function setSpotifyId(?string $spotifyId): static
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

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTimeImmutable $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection<int, PlaylistTrack>
     */
    public function getPlaylistTracks(): Collection
    {
        return $this->playlistTracks;
    }

    public function addPlaylistTrack(PlaylistTrack $playlistTrack): static
    {
        if (!$this->playlistTracks->contains($playlistTrack)) {
            $this->playlistTracks->add($playlistTrack);
            $playlistTrack->setTrack($this);
        }

        return $this;
    }

    public function removePlaylistTrack(PlaylistTrack $playlistTrack): static
    {
        if ($this->playlistTracks->removeElement($playlistTrack)) {
            if ($playlistTrack->getTrack() === $this) {
                $playlistTrack->setTrack(null);
            }
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
            if ($roomQueue->getTrack() === $this) {
                $roomQueue->setTrack(null);
            }
        }

        return $this;
    }
}
