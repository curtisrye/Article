<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\ViewModel;

use App\Editorial\Domain\Model\Tag as TagModel;

final class Tag implements \JsonSerializable
{
    public function __construct(
        private readonly int    $id,
        private readonly string $name,
    ) {}

    public static function createFromModel(TagModel $tag): self
    {
        return new self(
            $tag->id(),
            $tag->name(),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}