<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\Service;

use App\Blog\Post\Application\Model\CreateArticleCommand;
use App\Blog\Post\Domain\Entity\Article;
use App\Blog\Post\Domain\Entity\ArticleId;
use App\Blog\Post\Domain\Entity\AuthorId;
use App\Blog\Post\Domain\Repository\ArticleRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateArticleHandler implements MessageHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    private SerializerInterface $serializer;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->articleRepository = $articleRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    public function __invoke(CreateArticleCommand $createArticleCommand): string
    {
        $article = Article::create(
            new ArticleId(Uuid::uuid4()->toString()),
            $createArticleCommand->getTitle(),
            $createArticleCommand->getBody(),
            new AuthorId($createArticleCommand->getAuthor())
        );

        $this->articleRepository->save($article);

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($article, 'json');
    }
}
