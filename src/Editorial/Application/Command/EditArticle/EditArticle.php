<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\EditArticle;

final class EditArticle
{
    public function __construct(
        private readonly int                 $articleId,
        private readonly string              $title,
        private readonly string              $content,
    ) {}

    public function articleId(): int
    {
        return $this->articleId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }
}