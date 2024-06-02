<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\TagArticle;

use App\Core\Application\Command\CommandHandler;
use App\Editorial\Domain\Repository\ArticleRepository;
use App\Editorial\Domain\Repository\TagRepository;

class TagAnArticleHandler implements CommandHandler
{
    public function __construct(
        private readonly TagRepository $tagRepository,
        private readonly ArticleRepository $articleRepository
    ){}

    public function __invoke(TagAnArticle $command): void
    {
        $tag = $this->tagRepository->get($command->tagId());
        $article = $this->articleRepository->get($command->articleId());

        $article->addTag($tag);

        $this->articleRepository->save($article);
    }
}