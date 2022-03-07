<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\Repository;

use App\Blog\Post\Category\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(Category $comment): void;
}
