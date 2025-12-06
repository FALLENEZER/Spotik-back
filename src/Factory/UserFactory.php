<?php

namespace App\Factory;

use App\DTO\Input\User\UserInputDTO;
use App\DTO\Input\User\UserUpdateDTO;
use App\DTO\Output\User\UserOutputDTO;
use App\Entity\User;

class UserFactory
{
    public function makeUser(UserInputDTO $dto): User
    {
        $user = new User();

        $user->setName($dto->name);
        $user->setEmail($dto->email);
        $user->setPassword($dto->password);
        $user->setIsAdmin((bool)($dto->isAdmin ?? false));

        return $user;
    }

    public function makeUserInputDTO(array $data): UserInputDTO
    {
        $user = new UserInputDTO();

        $user->name = $data['name'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->password = $data['password'] ?? null;
        $user->isAdmin = $data['isAdmin'] ?? false;
        return $user;
    }

    public function makeUserUpdateDTO(array $data): UserUpdateDTO
    {
        $user = new UserUpdateDTO();

        $user->name = $data['name'] ?? null;
        $user->email = $data['email'] ?? null;
        $user->password = $data['password'] ?? null;
        // Для PATCH не подставляем значение по умолчанию, чтобы не затирать поле
        $user->isAdmin = $data['isAdmin'] ?? null;
        return $user;
    }

    public function makeUserOutputDTO(User $user): UserOutputDTO
    {
        $userDto = new UserOutputDTO();

        $userDto->id = $user->getId();
        $userDto->name = $user->getName();
        $userDto->email = $user->getEmail();
        $userDto->isAdmin = $user->isAdmin();

        return $userDto;
    }

    public function makeUserOutputDTOs(array $users): array
    {
        return array_map(fn($user) => $this->makeUserOutputDTO($user), $users);
    }

    public function editUser(User $user, UserUpdateDTO $dto): User
    {
        if ($dto->name !== null) {
            $user->setName($dto->name);
        }
        if ($dto->email !== null) {
            $user->setEmail($dto->email);
        }
        if ($dto->password !== null) {
            $user->setPassword($dto->password);
        }
        if ($dto->isAdmin !== null) {
            $user->setIsAdmin((bool)$dto->isAdmin);
        }
        return $user;
    }
}
