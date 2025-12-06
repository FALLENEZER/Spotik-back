<?php

namespace App\ResponseBuilder;

use App\Entity\Room;
use App\Factory\RoomFactory;
use App\Resource\RoomResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class RoomResponseBuilder
{
    public function __construct(private RoomResource $roomResource, private  RoomFactory $roomFactory)
    {
    }

    public function indexRoomResponse(array $rooms, $status = 200, $header = [], $isJson = true ): JsonResponse
    {
        $roomOutputDTOs = $this->roomFactory->makeRoomOutputDTOs($rooms);
        $roomResourses = $this->roomResource->roomCollection($roomOutputDTOs);

    }

    public function showRoomResponse(Room $room, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $roomOutputDTO = $this->roomFactory->makeRoomOutputDTO($room);
        $roomResource = $this->roomResource->roomItem($roomOutputDTO);
        return new JsonResponse($roomResource, $status, $headers, $isJson);
    }

}
