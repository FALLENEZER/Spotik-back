<?php

namespace App\ResponseBuilder;

use App\Entity\Track;
use App\Factory\TrackFactory;
use App\Resource\TrackResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class TrackResponseBuilder
{
    function __construct(private readonly TrackResource $resource, private readonly TrackFactory $factory)
    {
    }

    public function createTrackResponse(Track $track, $status = 201, $headers = [], $isJson = true): JsonResponse
    {
        $trackOutputDTO = $this->factory->makeTrackOutputDTO($track);
        $trackResource = $this->resource->trackItem($trackOutputDTO);
        return new JsonResponse($trackResource, $status, $headers, $isJson);
    }

    public function deleteTrackResponse($status = 204, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'deleted'], $status, $headers);
    }
}
