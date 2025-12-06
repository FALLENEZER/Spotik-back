<?php

namespace App\Service;

use App\DTO\Input\User\LoginDTO;
use App\Entity\User;
use App\Repository\UserRepository;

class AuthService
{
    function __construct(
        private UserRepository $userRepository,
        private PasswordHashService $passwordHashService,
    )
    {
    }

    public function login(LoginDTO $loginDTO) : ?User
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
}
