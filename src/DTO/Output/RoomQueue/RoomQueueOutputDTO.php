<?php

namespace App\DTO\Output\RoomQueue;

use App\DTO\Output\Room\RoomOutputDTO;
use App\DTO\Output\Track\TrackOutputDTO;
use App\DTO\Output\User\UserOutputDTO;

class RoomQueueOutputDTO
{
    public RoomOutputDTO $room;
    public TrackOutputDTO $track;
    public UserOutputDTO $addedBy;
}
