<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Event;

use App\Core\Application\Event\Event;
use App\Core\Domain\Event\EventDispatcher as EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyEventDispatcherInterface;

final class EventDispatcher implements EventDispatcherInterface
{
    private SymfonyEventDispatcherInterface $eventDispatcher;

    public function __construct(SymfonyEventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch(Event $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
}