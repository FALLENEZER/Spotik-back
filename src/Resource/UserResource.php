<?php

namespace App\Resource;

use App\DTO\Output\User\UserOutputDTO;
use Symfony\Component\Serializer\SerializerInterface;

class UserResource
{
    function __construct(private SerializerInterface $serializer)
    {
    }

    public function userItem(UserOutputDTO $user): string
    {
        return $this->serializer->serialize($user, 'json');
    }

    public function userCollection(array $users): string
    {
        return $this->serializer->serialize($users, 'json');
    }
}
