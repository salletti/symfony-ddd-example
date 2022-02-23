<?php

declare(strict_types=1);

namespace App\Blog\Category\Application\Event;

final class OnPublicationApprovedEvent
{
    private string $title;
    private string $body;
    private string $author;
    private string $categoryId;

    public function __construct(string $title, string $body, string $author, string $categoryId)
    {
        $this->title = $title;
        $this->body = $body;
        $this->author = $author;
        $this->categoryId = $categoryId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCategoryId(): string
    {
        return $this->categoryId;
    }
}
