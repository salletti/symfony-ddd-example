<?php

declare(strict_types=1);

namespace App\Blog\Post\Domain\OrmType;

use App\Blog\Post\Infrastructure\DoctrineType\AbstractUuidType;
use App\Blog\Post\Domain\Entity\ArticleId;

final class ArticleIdType extends AbstractUuidType
{
    public const ARTICLE_ID = 'article_id';

    public function getName(): string
    {
        return self::ARTICLE_ID;
    }

    protected function getValueObjectClassName(): string
    {
        return ArticleId::class;
    }
}
