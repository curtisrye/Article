<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\ConvertToDraft;

final class ConvertToDraft
{
    public function __construct(
        private readonly int $articleId,
        private readonly ?\DateTimeImmutable $releaseDate,
    )
    {}

    public function articleId(): int
    {
        return $this->articleId;
    }

    public function releaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }
}