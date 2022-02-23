<?php

declare(strict_types=1);

namespace App\Blog\User\Domain\Repository;

use App\Blog\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

    public function save(User $user): void;
}
