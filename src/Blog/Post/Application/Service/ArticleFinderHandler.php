<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\Service;

use App\Blog\Post\Application\Model\FindArticleQuery;
use App\Blog\Post\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Domain\Repository\CommentRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

final class ArticleFinderHandler implements MessageHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private CommentRepositoryInterface $commentRepository;
    private ObjectNormalizer $objectNormalizer;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CommentRepositoryInterface $commentRepository,
        ObjectNormalizer $objectNormalizer,
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->objectNormalizer = $objectNormalizer;
    }

    public function __invoke(FindArticleQuery $findArticleQuery): string
    {
        $articleId = $findArticleQuery->getArticleId();

        $article = $this->articleRepository->find($articleId);
        $comments = $this->commentRepository->findBy(['articleId' => $articleId]);

        $commentsNormalized = [];
        foreach ($comments as $comment) {
            $commentsNormalized[] = $this->objectNormalizer->normalize($comment);
        }

        $results = \array_merge(
            $this->objectNormalizer->normalize($article),
            ['comments' => $commentsNormalized]
        );

        return json_encode($results, JSON_THROW_ON_ERROR);
    }
}
