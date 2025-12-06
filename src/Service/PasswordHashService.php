<?php

namespace App\Service;

use App\DTO\Input\User\LoginDTO;
use App\DTO\Input\User\UserInputDTO;
use App\DTO\Input\User\UserUpdateDTO;
use App\Entity\User;

class PasswordHashService
{
    public function hashPassword(UserInputDTO|UserUpdateDTO $user): UserInputDTO|UserUpdateDTO
    {
        if (!empty($user->password)) {
            $user->password = password_hash($user->password, PASSWORD_ARGON2ID);
        }
        return $user;
    }

    public function verifyPassword(User $user, LoginDTO $dto): bool
    {
        if (empty($dto->password)) {
            return false;
        }

        return password_verify($dto->password, $user->getPassword());
    }
}
