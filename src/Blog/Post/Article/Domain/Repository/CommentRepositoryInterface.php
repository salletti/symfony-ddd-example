<?php

namespace App\Blog\Post\Article\Domain\Repository;

use App\Blog\Post\Article\Domain\Entity\Comment;

interface CommentRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function save(Comment $comment): void;
}
