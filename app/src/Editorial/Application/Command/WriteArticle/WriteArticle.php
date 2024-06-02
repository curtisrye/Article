<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\WriteArticle;

use App\Core\Application\Command\Command;
use App\Editorial\Domain\Model\Status;
use Webmozart\Assert\Assert;

final class WriteArticle implements Command
{
    public function __construct(
        private readonly string              $title,
        private readonly string              $content,
        private readonly int                 $userId,
        private readonly ?\DateTimeImmutable $releaseDate,
        private readonly string              $status,
    ) {
        Assert::inArray($this->status, Status::ALLOWED_STATUS);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function releaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function status(): string
    {
        return $this->status;
    }
}