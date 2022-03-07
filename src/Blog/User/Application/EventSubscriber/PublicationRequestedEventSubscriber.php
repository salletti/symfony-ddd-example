<?php

declare(strict_types=1);

namespace App\Blog\User\Application\EventSubscriber;

use App\Blog\Post\Article\Application\Event\OnPublicationRequestedEvent;
use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Application\Event\OnUserVerifiedEvent;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class PublicationRequestedEventSubscriber implements EventSubscriberInterface
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OnPublicationRequestedEvent::class => 'validateUser',
        ];
    }

    public function validateUser(OnPublicationRequestedEvent $event): void
    {
        /** @var User|null $user */
        $user = $this->userRepository->find($event->getAuthor());
        if (!$user) {
            throw new \InvalidArgumentException('author not found');
        }

        if (!\in_array('ROLE_EDITOR', $user->getRoles(), true)) {
            throw new \InvalidArgumentException('the author does not have the necessary permissions');
        }

        $this->eventDispatcher->dispatch(new OnUserVerifiedEvent(
            $event->getTitle(),
            $event->getBody(),
            $event->getAuthor(),
            $event->getCategorySlug()
        ));
    }
}
