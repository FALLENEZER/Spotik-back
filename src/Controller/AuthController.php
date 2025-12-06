<?php

namespace App\Controller;

use App\Factory\AuthFactory;
use App\Repository\UserRepository;
use App\ResponseBuilder\AuthResponseBuilder;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    function __construct(
        private AuthService $service,
        private AuthFactory $factory,
        private AuthResponseBuilder $responseBuilder,
    )
    {
    }

    #[Route('api/auth', name: 'auth', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $loginDto = $this->factory->makeLoginDTO($data);
        $user = $this->service->login($loginDto);

        if (!$user) {
            return $this->responseBuilder->errorResponse();
        }

        return $this->responseBuilder->successResponse($user);
    }
}
