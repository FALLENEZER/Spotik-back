<?php

namespace App\ResponseBuilder;

use App\DTO\Output\User\UserOutputDTO;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Resource\UserResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserResponseBuilder
{
    function __construct(private readonly UserResource $resource, private readonly UserFactory $userFactory)
    {
    }

    public function storeUserResponse(User $user, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->userFactory->makeUserOutputDTO($user);
        $userResource = $this->resource->userItem($userOutputDTO);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }

    public function indexUserResponse(array $users, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTOs = $this->userFactory->makeUserOutputDTOs($users);
        $userResource = $this->resource->userCollection($userOutputDTOs);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }

    public function showUserResponse(User $user, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->userFactory->makeUserOutputDTO($user);
        $userResource = $this->resource->userItem($userOutputDTO);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }
}
