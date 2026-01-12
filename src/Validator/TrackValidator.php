<?php

namespace App\Validator;

use App\DTO\Input\Track\TrackInputDTO;
use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TrackValidator
{
    function __construct(private ValidatorInterface $validator)
    {

    }

    public function validate(TrackInputDTO $track): void
    {
        $errors = $this->validator->validate($track);

        if (count($errors) > 0) {
            $message = [];

            foreach ($errors as $error) {
                $message[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new ValidationException($message);
        }
    }
}
