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

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(options: ['default' => 50])]
    private ?int $maxUsers = 50;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $isPrivate = false;

    #[ORM\ManyToOne(inversedBy: 'hostedRooms')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $host = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'memberRooms')]
    #[ORM\JoinTable(name: 'room_member')]
    private Collection $members;

    /**
     * @var Collection<int, RoomQueue>
     */
    #[ORM\OneToMany(mappedBy: 'room', targetEntity: RoomQueue::class, orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC', 'score' => 'DESC', 'addedAt' => 'ASC'])]
    private Collection $queueItems;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->members = new ArrayCollection();
        $this->queueItems = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getHost(): ?User
    {
        return $this->host;
    }

    public function setHost(?User $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $user): static
    {
        if (!$this->members->contains($user)) {
            $this->members->add($user);
            $user->addMemberRoom($this);
        }

        return $this;
    }

    public function removeMember(User $user): static
    {
        if ($this->members->removeElement($user)) {
            $user->removeMemberRoom($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RoomQueue>
     */
    public function getQueueItems(): Collection
    {
        return $this->queueItems;
    }

    public function addQueueItem(RoomQueue $queue): static
    {
        if (!$this->queueItems->contains($queue)) {
            $this->queueItems->add($queue);
            $queue->setRoom($this);
        }

        return $this;
    }

    public function removeQueueItem(RoomQueue $queue): static
    {
        if ($this->queueItems->removeElement($queue)) {
            if ($queue->getRoom() === $this) {
                $queue->setRoom(null);
            }
        }

        return $this;
    }
}
