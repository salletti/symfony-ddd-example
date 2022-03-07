<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Domain\Event;

use App\Blog\Post\Article\Domain\Entity\CommentId;
use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class CommentCreatedEvent extends Event implements DomainEventInterface
{
    protected \DateTimeImmutable $occur;
    protected CommentId $commentId;

    public function __construct(CommentId $commentId)
    {
        $this->commentId = $commentId;
        $this->occur = new \DateTimeImmutable();
    }

    public function getCommentId(): CommentId
    {
        return $this->commentId;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }
}
