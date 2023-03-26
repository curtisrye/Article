<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Model;

use App\Editorial\Domain\Exception\InvalidArticleEdition;
use Webmozart\Assert\Assert;


final class Article
{
    public const CHARACTER_TITLE_LIMIT = 128;

    private function __construct(
        private readonly ?int       $id,
        private string              $title,
        private string              $content,
        private readonly User       $user,
        private ?\DateTimeImmutable $releaseDate,
        private string              $status,
    ) {
        $this->validate();
    }

    public static function create(
        int $id,
        string $title,
        string $content,
        User $user,
        ?\DateTimeImmutable $releaseDate,
        string $status,
    ): self
    {
        return new self(
            id: $id,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: $status,
        );
    }

    public static function createDraftArticle(
        string $title,
        string $content,
        User $user,
        ?\DateTimeImmutable $releaseDate,
    ): self
    {
        return new self(
            id: null,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: $releaseDate,
            status: Status::DRAFT,
        );
    }

    public static function createPublishedArticle(
        string $title,
        string $content,
        User $user,
    ): self
    {
        return new self(
            id: null,
            title: $title,
            content: $content,
            user: $user,
            releaseDate: new \DateTimeImmutable(),
            status: Status::PUBLISHED,
        );
    }

    public function edit(string $title, string $content): void
    {
        $this->guardAgainstEdit();

        $this->title = $title;
        $this->content = $content;

        $this->validate();
    }

    public function convertToDraft(?\DateTimeImmutable $releaseDate): void
    {
        if (Status::DRAFT === $this->status) {
            //Idempotency
            return;
        }

        if (Status::DELETED === $this->status) {
            throw InvalidArticleEdition::cannotBeEditedFromDeletedStatus();
        }

        $this->status = Status::DRAFT;
        $this->releaseDate = $releaseDate;

        $this->validate();
    }

    public function publish(): void
    {
        if (Status::PUBLISHED === $this->status) {
            //Idempotency
            return;
        }

        if (Status::DELETED === $this->status) {
            throw InvalidArticleEdition::cannotBeEditedFromDeletedStatus();
        }

        $this->status = Status::PUBLISHED;
        $this->releaseDate = new \DateTimeImmutable();

        $this->validate();
    }

    public function delete(): void
    {
        if (Status::DELETED === $this->status) {
            //Idempotency
            return;
        }

        $this->status = Status::DELETED;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function releaseDate(): ?\DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function status(): string
    {
        return $this->status;
    }

    private function validate(): void
    {
        Assert::lengthBetween(
            value: $this->title,
            min: 0,
            max: self::CHARACTER_TITLE_LIMIT,
            message: sprintf('Title cannot exceed %s characters.', self::CHARACTER_TITLE_LIMIT)
        );

        if (null !== $this->releaseDate && Status::DRAFT === $this->status) {
            Assert::greaterThan($this->releaseDate, new \DateTimeImmutable());
        }

        Assert::inArray($this->status, Status::ALL);
    }

    private function guardAgainstEdit(): void
    {
        if (Status::DELETED === $this->status) {
            throw InvalidArticleEdition::cannotBeEditedFromDeletedStatus();
        }

        if (Status::DRAFT !== $this->status) {
            throw InvalidArticleEdition::cannotBeEditedFromStatus(status: $this->status);
        }
    }
}