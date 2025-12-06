<?php

namespace App\DTO\Input\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserInputDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim', message: "Name is required")]
    public ?string $name = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim', message: "Email is required")]
    #[Assert\Email(message: "Email is not valid")]
    public ?string $email = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim', message: "Password is required")]
    #[Assert\Length(
        min: 8,
        minMessage: "Password must be at least {{ limit }} characters long",
        max: 255,
        maxMessage: "Password must be at least {{ limit }} characters long",
    )]
    public ?string $password = null;

    #[Assert\NotNull]
    public ?bool $isAdmin = false;
}
