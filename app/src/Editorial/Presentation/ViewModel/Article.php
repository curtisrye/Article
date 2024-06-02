<?php

declare(strict_types=1);

namespace App\Editorial\Presentation\ViewModel;

use App\Editorial\Domain\Model\Article as ArticleModel;
use App\Editorial\Domain\Model\Tag as TagModel;
use App\Editorial\Domain\Model\User;

final class Article implements \JsonSerializable
{
    private function __construct(
        private readonly int                 $id,
        private readonly string              $title,
        private readonly string              $content,
        private readonly User                $author,
        private readonly ?\DateTimeImmutable $releaseDate,
        private readonly string              $status,
        private readonly array               $tags,
    ) {
    }

    public static function fromModel(ArticleModel $model): self
    {
        return new self(
            id: $model->id(),
            title: $model->title(),
            content: $model->content(),
            author: $model->user(),
            releaseDate: $model->releaseDate(),
            status: $model->status(),
            tags: $model->tags(),
        );
    }

    public function toArray() :array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author->firstname().' '.$this->author->lastname(),
            'releaseDate' => $this->releaseDate?->format(DATE_ATOM),
            'status' => $this->status,
            'tags' => array_map(static fn (TagModel $tag) => Tag::createFromModel($tag), $this->tags),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}