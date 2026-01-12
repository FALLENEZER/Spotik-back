<?php

namespace App\DTO\Input\Room;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class RoomCreateInputDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Length(min: 3, max: 50)]
    public ?string $name = null;

    #[Assert\Positive]
    #[Assert\LessThanOrEqual(50)]
    public ?int $maxUsers = 50;

    #[Assert\NotNull]
    public ?User $host = null;

    public ?bool $isPrivate = false;
}
