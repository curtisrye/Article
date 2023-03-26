<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\PublishArticle;

final class PublishArticle
{
    public function __construct(private readonly int $articleId)
    {}

    public function articleId(): int
    {
        return $this->articleId;
    }
}