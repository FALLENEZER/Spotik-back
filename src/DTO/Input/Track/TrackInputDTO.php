<?php

namespace App\DTO\Input\Track;

use Symfony\Component\Validator\Constraints as Assert;

class TrackInputDTO
{
    #[Assert\NotBlank(allowNull: null, normalizer: 'trim', message: "Path is required")]
    public ?string $path = null;

    #[Assert\NotBlank(allowNull: null, normalizer: 'trim', message: "Name is required")]
    public ?string $name = null;

    public ?string $artist = null;

    public ?string $imageUrl = null;

    #[Assert\Positive]
    #[Assert\NotNull]
    public ?int $duration = null;

    public ?\DateTimeImmutable $releaseDate = null;

}
