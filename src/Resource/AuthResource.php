<?php

namespace App\Resource;

use App\DTO\Output\User\UserOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class AuthResource
{
    public function __construct(private SerializerInterface $serializer)
    {}

    public function authItem(UserOutputDTO $user): string
    {
        return $this->serializer->serialize($user, 'json');
    }
}
