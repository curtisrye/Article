<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\ConvertToDraft;

use App\Editorial\Domain\Repository\ArticleRepository;

class ConvertToDraftHandler
{
    public function __construct(private readonly ArticleRepository $articleRepository)
    {}

    public function __invoke(ConvertToDraft $command): void
    {
        $article = $this->articleRepository->get($command->articleId());

        $article->convertToDraft($command->releaseDate());

        $this->articleRepository->save($article);
    }
}