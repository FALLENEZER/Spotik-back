<?php

namespace App\Service;

use App\DTO\Input\User\UserInputDTO;
use App\DTO\Input\User\UserUpdateDTO;

class PasswordHashService
{
    public function hashPassword(UserInputDTO|UserUpdateDTO $user): UserInputDTO|UserUpdateDTO
    {
        if (!empty($user->password)) {
            $user->password = password_hash($user->password, PASSWORD_ARGON2ID);
        }
        return $user;
    }
}
