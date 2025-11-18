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

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'playlists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, PlaylistTrack>
     */
    #[ORM\OneToMany(mappedBy: 'playlist', targetEntity: PlaylistTrack::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $playlistTracks;

    /**
     * @var Collection<int, FavoritePlaylist>
     */
    #[ORM\OneToMany(mappedBy: 'playlist', targetEntity: FavoritePlaylist::class, orphanRemoval: true)]
    private Collection $favorites;

    /**
     * @var Collection<int, RoomQueue>
     */
    #[ORM\OneToMany(mappedBy: 'playlist', targetEntity: RoomQueue::class)]
    private Collection $roomQueues;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->playlistTracks = new ArrayCollection();
        $this->favorites = new ArrayCollection();
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

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

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
            $playlistTrack->setPlaylist($this);
        }

        return $this;
    }

    public function removePlaylistTrack(PlaylistTrack $playlistTrack): static
    {
        if ($this->playlistTracks->removeElement($playlistTrack)) {
            if ($playlistTrack->getPlaylist() === $this) {
                $playlistTrack->setPlaylist(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavoritePlaylist>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(FavoritePlaylist $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setPlaylist($this);
        }

        return $this;
    }

    public function removeFavorite(FavoritePlaylist $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            if ($favorite->getPlaylist() === $this) {
                $favorite->setPlaylist(null);
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
            $roomQueue->setPlaylist($this);
        }

        return $this;
    }

    public function removeRoomQueue(RoomQueue $roomQueue): static
    {
        if ($this->roomQueues->removeElement($roomQueue)) {
            if ($roomQueue->getPlaylist() === $this) {
                $roomQueue->setPlaylist(null);
            }
        }

        return $this;
    }
}
