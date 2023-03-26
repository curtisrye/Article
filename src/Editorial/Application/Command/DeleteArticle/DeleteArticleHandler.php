<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\DeleteArticle;

use App\Editorial\Domain\Repository\ArticleRepository;

class DeleteArticleHandler
{
    public function __construct(private readonly ArticleRepository $articleRepository)
    {}

    public function __invoke(DeleteArticle $command): void
    {
        $article = $this->articleRepository->get($command->articleId());

        $article->delete();

        $this->articleRepository->save($article);
    }
}