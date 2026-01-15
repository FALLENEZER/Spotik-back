<?php

namespace App\ResponseBuilder;

use App\Entity\RoomQueue;
use App\Factory\RoomQueueFactory;
use App\Resource\RoomQueueResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomQueueResponseBuilder
{
    function __construct(
        private readonly RoomQueueResource $resource,
        private readonly RoomQueueFactory $factory)
    {

    }

    public function createRoomQueueResponse(RoomQueue $roomQueue, $status = 201, $headers = [], $isJson = true): JsonResponse
    {
        $roomQueueOutputDTO = $this->factory->makeRoomQueueOutputDTO($roomQueue);
        $roomQueueResource = $this->resource->roomQueueItem($roomQueueOutputDTO);
        return new JsonResponse($roomQueueResource, $status, $headers, $isJson);
    }


}
