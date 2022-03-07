<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\FindArticleQuery;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Article\Domain\Repository\CommentRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ArticleFinderHandler implements MessageHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private CommentRepositoryInterface $commentRepository;
    private SerializerInterface $serializer;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CommentRepositoryInterface $commentRepository,
        SerializerInterface $serializer,
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(FindArticleQuery $findArticleQuery): string
    {
        $articleId = $findArticleQuery->getArticleId();

        $article = $this->articleRepository->find($articleId);
        $comments = $this->commentRepository->findBy(['articleId' => $articleId]);

        $commentsNormalized = [];
        foreach ($comments as $comment) {
            $commentsNormalized[] = $this->serializer->normalize($comment);
        }

        $results = \array_merge(
            $this->serializer->normalize($article),
            ['comments' => $commentsNormalized]
        );

        return json_encode($results, JSON_THROW_ON_ERROR);
    }
}
