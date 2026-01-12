<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\ResponseBuilder\UserResponseBuilder;
use App\Service\PasswordHashService;
use App\Service\UserService;
use App\Validator\UserValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/api/users', name: 'users_')]
class UserController extends AppController
{
    function __construct(
        private readonly UserService         $service,
        private readonly PasswordHashService $passwordHashService,
        private readonly UserFactory         $factory,
        private readonly UserValidator       $validator,
        private readonly UserResponseBuilder $builder,
    )
    {
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->service->index();
        return $this->builder->indexUserResponse($users);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userInputDTO = $this->factory->makeUserInputDTO($data);
        $this->validator->validate($userInputDTO);
        $userInputDTO = $this->passwordHashService->hashPassword($userInputDTO);
        $user = $this->service->create($userInputDTO);
        return $this->builder->storeUserResponse($user);
    }

    #[Route('/{user<\d+>}', name: 'show', methods: ['get'])]
    public function show(User $user): JsonResponse
    {
        return $this->builder->showUserResponse($user);
    }

    #[Route('/{user<\d+>}', name: 'edit', methods: ['PATCH'])]
    public function update(Request $request, User $user): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $updateUserDTO = $this->factory->makeUserUpdateDTO($data);
        $this->validator->validate($updateUserDTO);
        $userInputDTO = $this->passwordHashService->hashPassword($updateUserDTO);
        $user = $this->service->update($user, $updateUserDTO);
        return $this->builder->updateUserResponse($user);
    }

    #[Route('/{user<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);
        return $this->builder->destroyUserResponse();
    }

}

