<?php

declare(strict_types=1);

namespace App\Blog\User\Application\Service;

use App\Blog\User\Domain\Entity\Email;
use App\Blog\User\Domain\Entity\User;
use App\Blog\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateUserService
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = $serializer;
    }

    public function handle(string $email, array $roles, string $password): string
    {
        $user = User::registerUser(
          new Email($email),
          $roles,
          $password
        );

        $this->userRepository->save($user);

        foreach ($user->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }

        return $this->serializer->serialize($user, 'json');
    }
}
