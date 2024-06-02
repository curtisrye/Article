<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\EditArticle;

use App\Core\Application\Command\CommandHandler;
use App\Editorial\Domain\Repository\ArticleRepository;

class EditArticleHandler implements CommandHandler
{
    public function __construct(private readonly ArticleRepository $articleRepository)
    {}

    public function __invoke(EditArticle $command): void
    {
        $article = $this->articleRepository->get($command->articleId());

        $article->edit($command->title(), $command->content());

        $this->articleRepository->save($article);
    }
}