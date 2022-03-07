<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Model;

final class CreateCategoryCommand
{
    private string $name;
    private string $slug;

    public function __construct(string $name, string $slug)
    {
        $this->name = $name;
        $this->slug = $slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
