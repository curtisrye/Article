<?php

declare(strict_types=1);

namespace App\Editorial\Domain\ValueObject;

final class PaginatedArticleResults
{
    public function __construct(
        private readonly int   $totalResult,
        private readonly array $items,
    )
    {}

    public function totalResult(): int
    {
        return $this->totalResult;
    }

    public function items(): array
    {
        return $this->items;
    }
}