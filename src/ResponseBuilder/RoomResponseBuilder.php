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

    public function createRoomResponse(Room $room, $status = 201, $headers = [], $isJson = true): JsonResponse
    {
        $roomOutputDTO = $this->roomFactory->makeRoomOutputDTO($room);
        $roomResource = $this->roomResource->roomItem($roomOutputDTO);
        return new JsonResponse($roomResource, $status, $headers, $isJson);
    }

    public function indexRoomResponse(array $rooms, $status = 200, $header = [], $isJson = true ): JsonResponse
    {
        $roomOutputDTOs = $this->roomFactory->makeRoomOutputDTOs($rooms);
        $roomResources = $this->roomResource->roomCollection($roomOutputDTOs);
        return new JsonResponse($roomResources, $status, $header, $isJson);
    }

    public function showRoomResponse(Room $room, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $roomOutputDTO = $this->roomFactory->makeRoomOutputDTO($room);
        $roomResource = $this->roomResource->roomItem($roomOutputDTO);
        return new JsonResponse($roomResource, $status, $headers, $isJson);
    }

    public function joinRoomResponse(Room $room, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $roomDTO = $this->roomFactory->makeRoomOutputDTO($room);
        $roomResource = $this->roomResource->roomItem($roomDTO);
        return new JsonResponse($roomResource, $status, $headers, $isJson);
    }

    public function leaveRoomResponse($status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'left'], $status, $headers);
    }

    public function destroyRoomResponse($status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'deleted'], $status, $headers);
    }
}
