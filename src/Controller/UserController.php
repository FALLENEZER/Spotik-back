<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\ResponseBuilder\UserResponseBuilder;
use App\Service\UserService;
use App\Validator\UserValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends  AbstractController
{
    function __construct(
        private readonly UserService $service,
        private readonly UserFactory $factory,
        private readonly UserValidator $validator,
        private readonly UserResponseBuilder $builder,
    )
    {
    }

    #[Route('api/users', name: 'users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->service->index();
        return $this->builder->indexUserResponse($users);
    }

    #[Route('api/users', name: 'users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $userInputDTO = $this->factory->makeUserInputDTO($data);
        $this->validator->validate($userInputDTO);
        $user = $this->service->store($userInputDTO);
        return $this->builder->storeUserResponse($user);
    }

    #[Route('api/users/{user}', name: 'users_show', methods: ['get'])]
    public function show(User $user): JsonResponse
    {
        return $this->builder->showUserResponse($user);
    }

//    #[Route('api/users/{user}', name: 'users_edit', methods: ['PATCH'])]
//    public function update(Request $request): JsonResponse
//    {
//        $data = json_decode($request->getContent(), true);
//
////        $updateUserDTO = $this->factory->
//    }
}

