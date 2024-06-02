<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\TagArticle;

use App\Core\Application\Command\Command;

final class TagAnArticle implements Command
{
    public function __construct(
        private readonly int                 $tagId,
        private readonly int                 $articleId,
    ) {}

    public function tagId(): int
    {
        return $this->tagId;
    }

    public function articleId(): int
    {
        return $this->articleId;
    }
}