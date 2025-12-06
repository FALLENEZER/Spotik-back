<?php

namespace App\DTO\Input\User;

use Symfony\Component\Validator\Constraints as Assert;

class LoginDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim')]
    #[Assert\Length(min: 8, max: 255)]
    public ?string $password = null;
}
