<?php

namespace App\DTO\Output\Track;

class TrackOutputDTO
{
    public ?string $path = null;

    public ?string $name = null;
    public ?string $artist = null;
    public ?string $imageUrl = null;
    public ?int $duration = null;
}
