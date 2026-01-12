<?php

namespace App\Service;

use App\DTO\Input\User\LoginDTO;
use App\Entity\User;
use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Validator\UserValidator;

class AuthService
{
    function __construct(
        private UserRepository         $userRepository,
        private readonly UserValidator $validator,
        private UserFactory            $userFactory,
        private PasswordHashService    $passwordHashService,
    )
    {
    }

    public function login(LoginDTO $loginDTO): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $loginDTO->email]);

        if (!$user) {
            return null;
        }

        if (!$this->passwordHashService->verifyPassword($user, $loginDTO)) {
            return null;
        }

        return $user;
    }

    public function register(array $data): User
    {
        $userInputDto = $this->userFactory->makeUserInputDTO($data);
        $this->validator->validate($userInputDto);
        $userInputDto = $this->passwordHashService->hashPassword($userInputDto);
        $user = $this->userFactory->makeUser($userInputDto);

        return $this->userRepository->create($user);
    }
}
