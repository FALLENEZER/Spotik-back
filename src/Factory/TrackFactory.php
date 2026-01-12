<?php

namespace App\Factory;

use App\DTO\Input\Track\TrackInputDTO;
use App\DTO\Output\Track\TrackOutputDTO;
use App\Entity\Track;

class TrackFactory
{
    public function makeTrack(TrackInputDTO $dto) : Track
    {
        $track = new Track();

        $track->setName($dto->name);
        $track->setPath($dto->path);
        $track->setArtist($dto->artist);
        $track->setImageUrl($dto->imageUrl);
        $track->setDuration($dto->duration);
        $track->setReleaseDate(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

        return $track;
    }

    public function makeTrackInputDTO(array $data) : TrackInputDTO
    {
        $track = new TrackInputDTO();

        $track->name = $data['name'] ?? null;
        $track->path = $data['path'] ?? null;
        $track->artist = $data['artist'] ?? null;
        $track->imageUrl = $data['imageUrl'] ?? null;
        $track->duration = $data['duration'] ?? null;

        return $track;
    }

    public function makeTrackOutputDTO(Track $track) : TrackOutputDTO
    {
        $trackDto = new TrackOutputDTO();

        $trackDto->name = $track->getName();
        $trackDto->path = $track->getPath();
        $trackDto->artist = $track->getArtist();
        $trackDto->imageUrl = $track->getImageUrl();
        $trackDto->duration = $track->getDuration();

        return $trackDto;
    }
}
