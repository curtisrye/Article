<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\CreateTag;

use App\Core\Application\Command\Command;

final class CreateTag implements Command
{
    public function __construct(
        private readonly string $name,
    ) {}

    public function name(): string
    {
        return $this->name;
    }
}