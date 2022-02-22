<?php

declare(strict_types=1);

namespace App\Blog\Post\Domain\Entity;

use App\Shared\ValueObject\EmailValueObject;

final class Email extends EmailValueObject
{
    private string $value;

    public function __construct(string $value)
    {
        $this->ensureIsValidEmail($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
