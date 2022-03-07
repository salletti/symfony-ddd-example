<?php

declare(strict_types=1);

namespace App\Blog\Post\Category\Application\Service;

use App\Blog\Post\Category\Application\Model\CreateCategoryCommand;
use App\Blog\Post\Category\Domain\Entity\Category;
use App\Blog\Post\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\Post\Shared\Domain\Entity\ValueObject\CategoryId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateCategoryService implements MessageHandlerInterface
{
    private EventDispatcherInterface $eventDispatcher;

    private CategoryRepositoryInterface $categoryRepository;

    private SerializerInterface $serializer;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->categoryRepository = $categoryRepository;
        $this->serializer = $serializer;
    }

    public function __invoke(CreateCategoryCommand $createCategoryCommand): string
    {
        $category = Category::create(
            new CategoryId(Uuid::uuid4()->toString()),
            $createCategoryCommand->getName(),
            $createCategoryCommand->getSlug()
        );

        $this->categoryRepository->save($category);

        foreach ($category->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($category, 'json');
    }
}
