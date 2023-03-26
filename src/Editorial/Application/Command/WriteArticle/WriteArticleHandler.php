<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\WriteArticle;

use App\Editorial\Application\Exception\StatusNotAllowed;
use App\Editorial\Domain\Model\Article;
use App\Editorial\Domain\Model\Status;
use App\Editorial\Domain\Model\User;
use App\Editorial\Domain\Repository\ArticleRepository;
use App\Editorial\Domain\Repository\UserRepository;

class WriteArticleHandler
{
    public function __construct(
        private readonly UserRepository    $userRepository,
        private readonly ArticleRepository $articleRepository,
    ){}

    public function __invoke(WriteArticle $command): void
    {
        $user = $this->userRepository->get($command->userId());

        if (Status::DRAFT === $command->status()) { $article = $this->createDraftArticle($command, $user); }

        else if (Status::PUBLISHED === $command->status()) { $article = $this->createPublishedArticle($command, $user); }

        else (throw StatusNotAllowed::create($command->status()));

        $this->articleRepository->save($article);
    }

    private function createDraftArticle(WriteArticle $command, User $user): Article
    {
        return Article::createDraftArticle(
            title: $command->title(),
            content: $command->content(),
            user: $user,
            releaseDate: $command->releaseDate(),
        );
    }

    private function createPublishedArticle(WriteArticle $command, User $user): Article
    {
        return Article::createPublishedArticle(
            title: $command->title(),
            content: $command->content(),
            user: $user,
        );
    }
}