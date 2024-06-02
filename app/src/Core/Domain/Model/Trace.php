<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

final class Trace
{
    public function __construct(
        private readonly string             $modelName,
        private readonly array              $oldFields,
        private readonly array              $newFields,
        private readonly \DateTimeInterface $updatedAt,
        private readonly int                $updatedBy,
    ) {}

    public function modelName(): string
    {
        return $this->modelName;
    }

    public function oldFields(): array
    {
        return $this->oldFields;
    }

    public function newFields(): array
    {
        return $this->newFields;
    }

    public function updatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function updatedBy(): int
    {
        return $this->updatedBy;
    }
}