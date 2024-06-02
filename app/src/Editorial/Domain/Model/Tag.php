<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Model;

final class Tag
{
    public function __construct(
        private readonly ?int   $id,
        private readonly string $name,
        private readonly int $counter
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function counter(): int
    {
        return $this->counter;
    }
}