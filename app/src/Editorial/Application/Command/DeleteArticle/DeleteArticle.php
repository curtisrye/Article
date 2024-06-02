<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\DeleteArticle;

use App\Core\Application\Command\Command;

final class DeleteArticle implements Command
{
    public function __construct(private readonly int $articleId)
    {}

    public function articleId(): int
    {
        return $this->articleId;
    }
}