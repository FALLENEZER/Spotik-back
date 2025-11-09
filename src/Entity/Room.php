<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    private ?int $maxUsers = null;

    #[ORM\Column]
    private ?bool $isPrivate = null;

    #[ORM\ManyToOne(inversedBy: 'rooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $hostId = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'rooms')]
    private Collection $гы�users;

    public function __construct()
    {
        $this->гы�users = new ArrayCollection();
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

    public function getMaxUsers(): ?int
    {
        return $this->maxUsers;
    }

    public function setMaxUsers(int $maxUsers): static
    {
        $this->maxUsers = $maxUsers;

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->isPrivate;
    }

    public function setIsPrivate(bool $isPrivate): static
    {
        $this->isPrivate = $isPrivate;

        return $this;
    }

    public function getHostId(): ?User
    {
        return $this->hostId;
    }

    public function setHostId(?User $hostId): static
    {
        $this->hostId = $hostId;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getгы�users(): Collection
    {
        return $this->гы�users;
    }

    public function addUser(User $user): static
    {
        if (!$this->гы�users->contains($user)) {
            $this->гы�users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->гы�users->removeElement($user);

        return $this;
    }
}
