<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\Event;

use App\Blog\Post\Article\Domain\Entity\ArticleId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ArticleCreatedEvent extends Event implements DomainEventInterface
{
    protected \DateTimeImmutable $occur;
    protected ArticleId $articleId;

    public function __construct(ArticleId $articleId)
    {
        $this->articleId = $articleId;
        $this->occur = new \DateTimeImmutable();
    }

    public function getArticleId(): ArticleId
    {
        return $this->articleId;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }
}
