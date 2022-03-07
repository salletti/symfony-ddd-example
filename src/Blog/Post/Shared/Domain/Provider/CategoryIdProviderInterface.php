<?php

namespace App\Blog\Post\Shared\Domain\Provider;

interface CategoryIdProviderInterface
{
    public function bySlug(string $slug): string;
}
