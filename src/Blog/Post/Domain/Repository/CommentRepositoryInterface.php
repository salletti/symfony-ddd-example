<?php

namespace App\Blog\Post\Domain\Repository;

use App\Blog\Post\Domain\Entity\Comment;

interface CommentRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function save(Comment $comment): void;
}
