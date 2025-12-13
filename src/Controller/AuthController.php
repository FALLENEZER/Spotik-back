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
        private UserRepository $userRepository,
    )
    {
    }

    #[Route('/api/auth/login', name: 'auth_login', methods: ['POST'])]
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

    #[Route('/api/auth/me', name: 'auth_me', methods: ['GET'])]
    public function me(Request $request): JsonResponse
    {
        // Попытка получить email из query (?email=) или из заголовка Authorization: Bearer <email>
        $email = $request->query->get('email');

        if (!$email) {
            $authorization = $request->headers->get('Authorization');
            if ($authorization && str_starts_with($authorization, 'Bearer ')) {
                $email = substr($authorization, 7);
            }
        }

        if (!$email) {
            return $this->responseBuilder->errorResponse(401);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            return $this->responseBuilder->errorResponse(401);
        }

        return $this->responseBuilder->successResponse($user);
    }
}
