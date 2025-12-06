<?php

namespace App\DTO\Input\Room;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\DBAL\Types\Types;

class RoomInputDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    private ?string $name = null;

    #[Assert\LessThanOrEqual(value: 50)]
    #[Assert\Positive]
    private ?int $maxUsers = 50;

    #[Assert\Type(Types::BOOLEAN)]
    private ?bool $isPrivate = false;

    #[Assert\NotNull]
    #[Assert\Type(User::class)]
    private ?User $host = null;
}
