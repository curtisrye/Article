<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\ViewModel;

use App\Editorial\Application\Paginator\Paginator;
use App\Editorial\Domain\ValueObject\PaginatedArticleResults;

final class ArticleCollection implements \JsonSerializable
{
    public function __construct(
        private readonly Paginator               $paginator,
        private readonly PaginatedArticleResults $paginatedResults,
    )
    {}

    public function toArray(): array
    {
        $items = [];
        foreach ($this->paginatedResults->items() as $article) {
            $items[] = Article::fromModel($article);
        }

        $totalPage = ceil($this->paginatedResults->totalResult()/$this->paginator->getItemsPerPage());

        return [
            'page' => $this->paginator->getPage(),
            'totalPage' => $totalPage,
            'items' => $items,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}