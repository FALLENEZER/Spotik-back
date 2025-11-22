<?php

namespace App\DTO\Input\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    public ?string $name = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    public ?string $email = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    public ?string $password = null;

    public ?bool $isAdmin = false;
}
