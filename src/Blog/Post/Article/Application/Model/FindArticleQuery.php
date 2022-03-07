<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Model;

final class FindArticleQuery
{
    private string $articleId;

    public function __construct(string $articleId)
    {
        $this->articleId = $articleId;
    }

    public function getArticleId(): string
    {
        return $this->articleId;
    }
}
