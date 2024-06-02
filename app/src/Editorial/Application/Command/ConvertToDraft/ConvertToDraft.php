<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\ConvertToDraft;

use App\Core\Application\Command\Command;

final class ConvertToDraft implements Command
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