<?php

declare(strict_types=1);

namespace App\Blog\Post\Domain\Entity;

class Comment
{
    private string $id;

    private Email $email;

    private string $message;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $updatedAt;

    private string $articleId;

    public function __construct(CommentId $commentId)
    {
        $this->id = $commentId->getValue();
    }

    public function getId(): CommentId
    {
        return new CommentId($this->id);
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getArticleId(): ArticleId
    {
        return new ArticleId($this->articleId);
    }

    public function setArticleId(ArticleId $articleId): void
    {
        $this->articleId = $articleId->getValue();
    }
}
