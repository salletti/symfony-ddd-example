<?php

namespace App\Blog\Post\Domain\Repository;

use App\Blog\Post\Domain\Entity\Article;

interface ArticleRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function save(Article $article): void;
}
