<?php

declare(strict_types=1);

namespace App\Blog\Post\Infrastructure\DoctrineType;

use Ramsey\Uuid\Doctrine\UuidType;

abstract class AbstractUuidType extends UuidType
{
    abstract protected function getValueObjectClassName(): string;
}
