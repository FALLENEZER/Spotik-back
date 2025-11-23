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
        $user->isAdmin = $data['isAdmin'] ?? false;
        return $user;
    }

    public function makeUserOutputDTO(User $user): UserOutputDTO
    {
        $userDto = new UserOutputDTO();

        $userDto->name = $user->getName();
        $userDto->email = $user->getEmail();
        $userDto->password = $user->getPassword();

        return $userDto;
    }

    public function makeUserOutputDTOs(array $users): array
    {
        return array_map(fn($user) => $this->makeUserOutputDTO($user), $users);
    }

    public function editUser(User $user, UserUpdateDTO $dto): User
    {
        $user->setName($dto->name);
        $user->setEmail($dto->email);
        $user->setPassword($dto->password);
        $user->setIsAdmin($dto->isAdmin);
        return $user;
    }
}
