<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Repository;

use App\Editorial\Domain\Model\Article;

interface ArticleRepository
{
    public function get(int $articleId): Article;

    public function getActive(int $articleId): Article;

    public function save(Article $article): void;
}