<?php

declare(strict_types=1);

namespace App\Blog\Post\Shared\Domain\Provider;

use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;

final class CategoryIdProvider implements CategoryIdProviderInterface
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function bySlug(string $slug): string
    {
        /** @var Category|null $category */
        $category = $this->categoryRepository->findOneBy(['slug' => $slug]);
        if (!$category) {
            throw new \InvalidArgumentException(\sprintf('category with slug %s not found', $slug));
        }

        return $category->getId();
    }
}
