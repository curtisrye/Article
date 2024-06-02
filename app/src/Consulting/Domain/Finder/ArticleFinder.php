<?php

declare(strict_types=1);

namespace App\Consulting\Domain\Finder;

interface ArticleFinder
{
    public function findAll(): array;
}