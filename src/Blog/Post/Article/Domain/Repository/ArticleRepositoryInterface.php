<?php

namespace App\Blog\Post\Article\Domain\Repository;

use App\Blog\Post\Article\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function save(Article $article): void;
}
