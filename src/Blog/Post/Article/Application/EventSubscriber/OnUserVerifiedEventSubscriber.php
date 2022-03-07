<?php

declare(strict_types=1);

namespace App\Blog\Post\Article\Application\EventSubscriber;

use App\Blog\Post\Article\Application\Model\CreateArticleCommand;
use App\Blog\Post\Shared\Domain\Provider\CategoryIdProviderInterface;
use App\Blog\User\Application\Event\OnUserVerifiedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OnUserVerifiedEventSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;
    private CategoryIdProviderInterface $categoryIdProvider;

    public function __construct(
        MessageBusInterface $messageBus,
        CategoryIdProviderInterface $categoryIdProvider
    ) {
        $this->messageBus = $messageBus;
        $this->categoryIdProvider = $categoryIdProvider;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnUserVerifiedEvent::class => 'createArticle',
        ];
    }

    public function createArticle(OnUserVerifiedEvent $event): void
    {
        $createArticleCommand = new CreateArticleCommand();
        $createArticleCommand->setTitle($event->getTitle());
        $createArticleCommand->setBody($event->getBody());
        $createArticleCommand->setAuthor($event->getAuthor());
        $createArticleCommand->setCategory(
            $this->categoryIdProvider->bySlug($event->getCategorySlug())
        );

        $this->messageBus->dispatch($createArticleCommand);
    }
}
