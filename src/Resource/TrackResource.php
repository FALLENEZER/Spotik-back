<?php

namespace App\Resource;

use App\DTO\Output\Track\TrackOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class TrackResource
{
    function __construct(private SerializerInterface $serializer)
    {
    }

    public function trackItem(TrackOutputDTO $track): string
    {
        return $this->serializer->serialize($track, 'json');
    }

    public function trackCollection(array $tracks): string
    {
        return $this->serializer->serialize($tracks, 'json');
    }
}
