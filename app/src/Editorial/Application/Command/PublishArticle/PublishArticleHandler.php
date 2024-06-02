<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\PublishArticle;

use App\Core\Application\Command\CommandHandler;
use App\Editorial\Domain\Repository\ArticleRepository;

class PublishArticleHandler implements CommandHandler
{
    public function __construct(private readonly ArticleRepository $articleRepository)
    {}

    public function __invoke(PublishArticle $command): void
    {
        $article = $this->articleRepository->get($command->articleId());

        $article->publish();

        $this->articleRepository->save($article);
    }
}