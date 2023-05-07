<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Domain\Event;

use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class CategoryCreatedEvent extends Event implements DomainEventInterface
{
    private CategoryId $categoryId;

    protected \DateTimeImmutable $occur;

    public function __construct(CategoryId $categoryId)
    {
        $this->categoryId = $categoryId;
        $this->occur = new \DateTimeImmutable();
    }

    public function getCategoryId(): CategoryId
    {
        return $this->categoryId;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }
}
