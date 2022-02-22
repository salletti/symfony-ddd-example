<?php

declare(strict_types=1);

namespace App\Blog\Post\Domain\OrmType;

use App\Blog\Post\Domain\Entity\CommentId;
use App\Blog\Post\Infrastructure\DoctrineType\AbstractUuidType;

final class CommentIdType extends AbstractUuidType
{
    public const COMMENT_ID = 'comment_id';

    public function getName(): string
    {
        return self::COMMENT_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return CommentId::class;
    }
}
