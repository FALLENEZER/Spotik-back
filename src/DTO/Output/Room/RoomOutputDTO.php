<?php

namespace App\DTO\Output\Room;

use App\DTO\Output\RoomQueue\RoomQueueShortOutputDTO;
use App\DTO\Output\User\UserShortDTO;
use App\Entity\RoomQueue;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

class RoomOutputDTO
{
    public ?string $name = null;

    public ?\DateTimeImmutable $createdAt = null;

    public ?int $maxUsers = 50;

    public ?bool $isPrivate = false;

    /** @var UserShortDTO */
    public UserShortDTO $host;

    /** @var UserShortDTO[] */
    public array $members;

    /** @var RoomQueueShortOutputDTO*/
    public array $queueItems;
}
