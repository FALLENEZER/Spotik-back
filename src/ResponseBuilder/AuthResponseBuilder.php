<?php

namespace App\ResponseBuilder;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Resource\AuthResource;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthResponseBuilder
{
    function __construct(
        private AuthResource                      $resource,
        private UserFactory                       $factory,
        private readonly JWTTokenManagerInterface $jwtManager)
    {

    }

    public function successResponse(User $user, $status = 200, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->factory->makeUserOutputDTO($user);
        $userResource = $this->resource->authItem($userOutputDTO);
        $token = $this->jwtManager->create($user);
        return new JsonResponse($token, $status, $headers, $isJson);
    }

    public function errorResponse($status = 401, $headers = []): JsonResponse
    {
        return new JsonResponse(['message' => 'Invalid credentials'], $status, $headers);
    }

    public function registerResponse(User $user, $status = 201, $headers = [], $isJson = true): JsonResponse
    {
        $userOutputDTO = $this->factory->makeUserOutputDTO($user);
        $userResource = $this->resource->authItem($userOutputDTO);
        return new JsonResponse($userResource, $status, $headers, $isJson);
    }
}
