<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\Entity;

use App\Blog\Post\Category\Domain\Event\CategoryCreatedEvent;
use App\Shared\Aggregate\AggregateRoot;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;

class Category extends AggregateRoot
{
    private string $id;

    private string $name;

    private string $slug;

    public function __construct(CategoryId $id, string $name, string $slug)
    {
        $this->id = $id->getValue();
        $this->name = $name;
        $this->slug = $slug;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public static function create(CategoryId $id, string $name, string $slug): self
    {
        $category = new Category(
          $id,
          $name,
          $slug
        );

        $category->recordDomainEvent(new CategoryCreatedEvent($id));

        return $category;
    }
}
