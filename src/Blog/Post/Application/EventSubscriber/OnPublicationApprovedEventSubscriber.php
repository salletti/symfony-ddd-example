<?php

declare(strict_types=1);

namespace App\Blog\Post\Application\EventSubscriber;

use App\Blog\Category\Application\Event\OnPublicationApprovedEvent;
use App\Blog\Post\Application\Model\CreateArticleCommand;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OnPublicationApprovedEventSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnPublicationApprovedEvent::class => 'createArticle',
        ];
    }

    public function createArticle(OnPublicationApprovedEvent $event): void
    {
        $createArticleCommand = new CreateArticleCommand();
        $createArticleCommand->setTitle($event->getTitle());
        $createArticleCommand->setBody($event->getBody());
        $createArticleCommand->setAuthor($event->getAuthor());
        $createArticleCommand->setCategory($event->getCategoryId());

        $this->messageBus->dispatch($createArticleCommand);
    }
}
