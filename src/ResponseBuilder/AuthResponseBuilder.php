<?php

namespace App\ResponseBuilder;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Resource\AuthResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthResponseBuilder
{
    function __construct(private AuthResource $resource, private UserFactory $factory)
    {

    }

    public function successResponse(User $user, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->factory->makeUserOutputDTO($user);
        $userResource = $this->resource->authItem($userOutputDTO);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }

    public function errorResponse($status = 401, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'Invalid credentials'], $status, $headers);
    }
}
