<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\UnTagArticle;

use App\Core\Application\Command\CommandHandler;
use App\Editorial\Domain\Repository\ArticleRepository;
use App\Editorial\Domain\Repository\TagRepository;

class UnTagArticleHandler implements CommandHandler
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly ArticleRepository $articleRepository
    ){}

    public function __invoke(UnTagArticle $command): void
    {
        $tag = $this->tagRepository->get($command->tagId());
        $article = $this->articleRepository->get($command->articleId());

        $article->removeTag($tag);

        $this->articleRepository->save($article);
    }
}