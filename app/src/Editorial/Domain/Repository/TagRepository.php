<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Repository;

use App\Editorial\Domain\Model\Tag;

interface TagRepository
{
    public function get(int $id): Tag;

    public function save(Tag $tag): void;
}