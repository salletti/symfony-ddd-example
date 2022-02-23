<?php

declare(strict_types=1);

namespace App\Blog\Category\Application\EventSubscriber;

use App\Blog\Category\Domain\Entity\Category;
use App\Blog\Category\Application\Event\OnPublicationApprovedEvent;
use App\Blog\Category\Domain\Repository\CategoryRepositoryInterface;
use App\Blog\User\Application\Event\UserVerifiedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserVerifiedEventSubscriber implements EventSubscriberInterface
{
    private CategoryRepositoryInterface $categoryRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserVerifiedEvent::class => 'retrieveCategoryId',
        ];
    }

    public function retrieveCategoryId(UserVerifiedEvent $event): void
    {
        /** @var Category|null $category */
        $category = $this->categoryRepository->findOneBy(['slug' => $event->getCategorySlug()]);
        if (!$category) {
            throw new \InvalidArgumentException(\sprintf('category with slug %s not found', $event->getCategorySlug()));
        }

        $this->eventDispatcher->dispatch(
            new OnPublicationApprovedEvent(
                $event->getTitle(),
                $event->getBody(),
                $event->getAuthor(),
                $category->getId()
            )
        );
    }
}
