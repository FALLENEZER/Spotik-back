<?php

namespace App\Factory;

use App\DTO\Input\User\LoginDTO;

class AuthFactory
{
    public function makeLoginDTO(array $data) : LoginDTO
    {
        $dto = new LoginDTO();

        $dto->email = $data['email'] ?? null;
        $dto->password = $data['password'] ?? null;

        return $dto;
    }
}
