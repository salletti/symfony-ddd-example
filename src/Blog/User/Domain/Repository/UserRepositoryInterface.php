<?php

declare(strict_types=1);

namespace App\Blog\User\Domain\Repository;

use App\Blog\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;
}
