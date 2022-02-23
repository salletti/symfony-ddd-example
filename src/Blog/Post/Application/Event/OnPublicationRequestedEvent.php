<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class OnPublicationRequestedEvent extends Event
{
    private string $title;

    private string $body;

    private string $author;

    private string $categorySlug;

    public function __construct(string $title, string $body, string $author, string $categorySlug)
    {
        $this->title = $title;
        $this->body = $body;
        $this->author = $author;
        $this->categorySlug = $categorySlug;
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

    public function getCategorySlug(): string
    {
        return $this->categorySlug;
    }
}
