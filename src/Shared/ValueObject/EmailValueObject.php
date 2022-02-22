<?php

declare(strict_types=1);

namespace App\Shared\ValueObject;

abstract class EmailValueObject
{
    protected function ensureIsValidEmail(string $email): void
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('The email <%s> is not valid', $email));
        }
    }
}
