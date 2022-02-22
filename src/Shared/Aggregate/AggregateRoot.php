<?php

declare(strict_types=1);

namespace App\Shared\Aggregate;

use App\Blog\Post\Domain\Event\DomainEventInterface;

abstract class AggregateRoot
{
    protected array $domainEvents;

    public function recordDomainEvent(DomainEventInterface $event): self
    {
        $this->domainEvents[] = $event;

        return $this;
    }

    public function pullDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

}
