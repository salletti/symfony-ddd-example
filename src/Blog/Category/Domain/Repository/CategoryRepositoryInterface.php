<?php

declare(strict_types=1);

namespace App\Blog\Category\Domain\Repository;

use App\Blog\Category\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(Category $comment): void;
}
