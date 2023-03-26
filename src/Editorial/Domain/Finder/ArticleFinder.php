<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Finder;

use App\Editorial\Application\Query\GetArticles\RetrieveArticles;
use App\Editorial\Domain\ValueObject\PaginatedArticleResults;

interface ArticleFinder
{
    public function retrieveArticles(RetrieveArticles $query): PaginatedArticleResults;
}