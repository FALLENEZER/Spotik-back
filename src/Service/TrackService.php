<?php

namespace App\Service;

use App\Entity\Track;
use App\Factory\TrackFactory;
use App\Repository\TrackRepository;
use App\Validator\TrackValidator;
use Symfony\Component\HttpFoundation\Request;

class TrackService
{
    function __construct(
        private readonly TrackRepository $repository,
        private readonly TrackFactory $factory,
        private readonly TrackValidator $validator,
    )
    {
    }

    public function create(array $data): Track
    {
        $trackDTO = $this->factory->makeTrackInputDTO($data);
        $this->validator->validate($trackDTO);
        $track = $this->factory->makeTrack($trackDTO);

        return $this->repository->store($track);
    }

    public function search(?string $name, ?string $artist): array
    {
        return $this->repository->search($name, $artist);
    }

    public function delete(Track $track): void
    {
        $this->repository->delete($track);
    }
}
