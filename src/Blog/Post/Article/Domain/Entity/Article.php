<?php

namespace App\Blog\Post\Article\Domain\Entity;

use App\Blog\Post\Article\Domain\Event\ArticleCreatedEvent;
use App\Blog\Post\Article\Domain\Event\CommentCreatedEvent;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use App\Shared\Aggregate\AggregateRoot;

class Article extends AggregateRoot
{
    private string $id;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $updatedAt;

    private string $body;

    private string $title;

    private string $author;

    private string $category;

    public function getCategory(): CategoryId
    {
        return new CategoryId($this->category);
    }

    public function setCategory(CategoryId $category): self
    {
        $this->category = $category->getValue();

        return $this;
    }

    public function __construct(ArticleId $id)
    {
        $this->id = $id->getValue();
    }

    public function getId(): ?ArticleId
    {
        return new ArticleId($this->id);
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): AuthorId
    {
        return new AuthorId($this->author);
    }

    public function setAuthor(AuthorId $author): self
    {
        $this->author = $author->getValue();

        return $this;
    }

    public static function create(
        ArticleId $articleId,
        string $title,
        string $body,
        AuthorId $authorId,
        CategoryId $categoryId
    ): self {
        $article = new self($articleId);
        $article->setTitle($title);
        $article->setBody($body);
        $article->setCreatedAt(new \DateTimeImmutable('now'));
        $article->setUpdatedAt(new \DateTimeImmutable('now'));
        $article->setAuthor($authorId);
        $article->setCategory($categoryId);

        $article->recordDomainEvent(new ArticleCreatedEvent($articleId));

        return $article;
    }

    public static function createComment(
        Article $article,
        CommentId $commentId,
        Email $email,
        string $message
    ): Comment {
        $comment = new Comment($commentId);
        $comment->setEmail($email);
        $comment->setArticleId($article->getId());
        $comment->setMessage($message);
        $comment->setCreatedAt(new \DateTimeImmutable('now'));
        $comment->setUpdatedAt(new \DateTimeImmutable('now'));

        $article->recordDomainEvent(new CommentCreatedEvent($commentId));

        return $comment;
    }
}
