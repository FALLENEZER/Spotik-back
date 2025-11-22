<?php

namespace App\Validator;

use App\DTO\Input\User\UserInputDTO;
use App\DTO\Input\User\UserUpdateDTO;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidator
{
    function __construct(private ValidatorInterface $validator)
    {

    }

    public function validate(UserInputDTO|UserUpdateDTO $user): void
    {
        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $message = [];

            foreach ($errors as $error) {
                $message[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new InvalidArgumentException(json_encode($message));
        }
    }
}
