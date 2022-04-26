<?php

namespace Majie\Fills\Fill\Exceptions;

class ValidateException extends \Exception
{

    private array $errors;

    public function __construct(array $errors, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }


    public function getFirstError(): string
    {
        return $this->errors[0] ?? '';
    }
}
