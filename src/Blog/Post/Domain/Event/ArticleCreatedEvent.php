<?php

declare(strict_types=1);

namespace App\Blog\Post\Domain\Event;

use App\Blog\Post\Domain\Entity\ArticleId;
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
