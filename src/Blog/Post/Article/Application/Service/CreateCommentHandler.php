<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\CreateCommentCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Entity\CommentId;
use App\Blog\Post\Article\Domain\Entity\Email;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Article\Domain\Repository\CommentRepositoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateCommentHandler implements MessageHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private CommentRepositoryInterface $commentRepository;
    private EventDispatcherInterface $eventDispatcher;
    private SerializerInterface $serializer;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        CommentRepositoryInterface $commentRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    public function __invoke(CreateCommentCommand $createCommentCommand): string
    {
        $article = $this->articleRepository->find($createCommentCommand->getArticleId());
        if (!$article) {
            throw new \InvalidArgumentException('article not found');
        }

        $comment = Article::createComment(
            $article,
            new CommentId(Uuid::uuid4()->toString()),
            new Email($createCommentCommand->getEmail()),
            $createCommentCommand->getMessage()
        );

        $this->commentRepository->save($comment);

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($comment, 'json');
    }
}
