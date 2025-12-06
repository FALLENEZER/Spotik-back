<?php

namespace App\Exception;

use Throwable;

class ValidationException extends \RuntimeException
{
    public function __construct(private array $errors)
    {
        parent::__construct(json_encode([
            'errors' => $errors
        ]));
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
