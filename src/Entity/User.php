<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    shortName: 'User'
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['user:read', 'user:write'])]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user:write'])]
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 6, max: 255, groups: ['create'])]
    private ?string $password = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:write'])]
    private ?bool $isAdmin = false;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\OneToMany(targetEntity: Room::class, mappedBy: 'host')]
    private Collection $hostedRooms;

    /**
     * @var Collection<int, Room>
     */
    #[ORM\ManyToMany(targetEntity: Room::class, mappedBy: 'members')]
    private Collection $memberRooms;

    /**
     * @var Collection<int, Playlist>
     */
    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'owner')]
    private Collection $playlists;

    /**
     * @var Collection<int, FavoritePlaylist>
     */
    #[ORM\OneToMany(targetEntity: FavoritePlaylist::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $favoritePlaylists;

    public function __construct()
    {
        $this->hostedRooms = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->memberRooms = new ArrayCollection();
        $this->favoritePlaylists = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): static
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getHostedRooms(): Collection
    {
        return $this->hostedRooms;
    }

    public function addHostedRoom(Room $room): static
    {
        if (!$this->hostedRooms->contains($room)) {
            $this->hostedRooms->add($room);
            $room->setHost($this);
        }

        return $this;
    }

    public function removeHostedRoom(Room $room): static
    {
        if ($this->hostedRooms->removeElement($room)) {
            if ($room->getHost() === $this) {
                $room->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getMemberRooms(): Collection
    {
        return $this->memberRooms;
    }

    public function addMemberRoom(Room $room): static
    {
        if (!$this->memberRooms->contains($room)) {
            $this->memberRooms->add($room);
        }

        return $this;
    }

    public function removeMemberRoom(Room $room): static
    {
        $this->memberRooms->removeElement($room);

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
            $playlist->setOwner($this);
        }

        return $this;
    }

    public function removePlaylist(Playlist $playlist): static
    {
        if ($this->playlists->removeElement($playlist)) {
            if ($playlist->getOwner() === $this) {
                $playlist->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavoritePlaylist>
     */
    public function getFavoritePlaylists(): Collection
    {
        return $this->favoritePlaylists;
    }

    public function addFavoritePlaylist(FavoritePlaylist $favoritePlaylist): static
    {
        if (!$this->favoritePlaylists->contains($favoritePlaylist)) {
            $this->favoritePlaylists->add($favoritePlaylist);
            $favoritePlaylist->setUser($this);
        }

        return $this;
    }

    public function removeFavoritePlaylist(FavoritePlaylist $favoritePlaylist): static
    {
        if ($this->favoritePlaylists->removeElement($favoritePlaylist)) {
            if ($favoritePlaylist->getUser() === $this) {
                $favoritePlaylist->setUser(null);
            }
        }

        return $this;
    }
}
