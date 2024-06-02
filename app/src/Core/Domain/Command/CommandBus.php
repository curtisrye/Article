<?php

declare(strict_types=1);

namespace App\Core\Domain\Command;

use App\Core\Application\Command\Command;

interface CommandBus
{
    public function handle(Command $command): void;
}