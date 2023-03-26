<?php

declare(strict_types=1);

namespace App\Editorial\Application\Paginator;

use Webmozart\Assert\Assert;

final class Paginator
{
    public function __construct(private readonly int $page, private readonly int $itemsPerPage)
    {
        Assert::greaterThanEq($page, 1);
        Assert::greaterThanEq($itemsPerPage, 1);
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getFirstResultOffset(): int
    {
        return max($this->page * $this->itemsPerPage - $this->itemsPerPage, 0);
    }
}