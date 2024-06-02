<?php

declare(strict_types=1);

namespace App\Editorial\Application\Query\GetArticles;

use App\Editorial\Application\Paginator\Paginator;

class RetrieveArticles
{
    public function __construct(
        private readonly int $userId,
        private readonly array $status,
        private readonly ?Paginator $paginator
    ) {}

    public function userId(): int
    {
        return $this->userId;
    }

    public function status(): array
    {
        return $this->status;
    }

    public function paginator(): ?Paginator
    {
        return $this->paginator;
    }
}