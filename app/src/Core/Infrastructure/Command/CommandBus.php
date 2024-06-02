<?php

declare(strict_types=1);

namespace App\Core\Infrastructure\Command;

use App\Core\Application\Command\Command;
use App\Core\Domain\Command\CommandBus as CommandBusInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    public function __construct(
      private readonly MessageBusInterface $bus
    ) {}

    public function handle(Command $command): void
    {
        $this->bus->dispatch($command);
    }
}