<?php

namespace App\Service;

use App\DTO\Input\User\UserInputDTO;
use App\DTO\Input\User\UserUpdateDTO;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;

class UserService
{
    function __construct(private UserRepository $repository, private UserFactory $userFactory)
    {
    }

    public function store(UserInputDTO $userInputDTO): User
    {
        $user = $this->userFactory->makeUser($userInputDTO);
        return $this->repository->store($user);
    }

    public function index(): array
    {
        return $this->repository->findAll();
    }

    public function update(User $user, UserUpdateDTO $userUpdateDTO): User
    {
        $user = $this->userFactory->editUser($user, $userUpdateDTO);
        return $this->repository->update($user);
    }
}
