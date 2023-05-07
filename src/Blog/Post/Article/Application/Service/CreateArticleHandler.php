<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\Service;

use App\Blog\Post\Article\Application\Model\CreateArticleCommand;
use App\Blog\Post\Article\Domain\Entity\Article;
use App\Blog\Post\Article\Domain\Entity\ArticleId;
use App\Blog\Post\Article\Domain\Entity\AuthorId;
use App\Blog\Post\Article\Domain\Repository\ArticleRepositoryInterface;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
final class CreateArticleHandler
{
    private ArticleRepositoryInterface $articleRepository;
    private EventDispatcherInterface $eventDispatcher;
    private SerializerInterface $serializer;
    private RequestStack $requestStack;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer,
        RequestStack $requestStack,
    ) {
        $this->articleRepository = $articleRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
        $this->requestStack = $requestStack;
    }

    public function __invoke(CreateArticleCommand $createArticleCommand): void
    {
        $article = Article::create(
            new ArticleId(Uuid::uuid4()->toString()),
            $createArticleCommand->getTitle(),
            $createArticleCommand->getBody(),
            new AuthorId($createArticleCommand->getAuthor()),
            new CategoryId($createArticleCommand->getCategory())
        );

        $this->articleRepository->save($article);

        $this->requestStack->getSession()->set(
            'last_article_created',
            $this->serializer->serialize($article, 'json')
        );

        foreach ($article->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
