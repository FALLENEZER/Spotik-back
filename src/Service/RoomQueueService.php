<?php

namespace App\Service;

class RoomQueueService
{
    function __construct(private readonly RoomPublisher $publisher)
    {

    }

}
