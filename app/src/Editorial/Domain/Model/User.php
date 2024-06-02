<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Model;

final class User
{
    private function __construct(
        private readonly int    $id,
        private readonly string $firstname,
        private readonly string $lastname,
    ) {}

    public static function create(int $id, string $firstname, string $lastname): self
    {
        return new self(
            id: $id,
            firstname: $firstname,
            lastname: $lastname,
        );
    }

    public function id(): int
    {
        return $this->id;
    }

    public function firstname(): string
    {
        return $this->firstname;
    }

    public function lastname(): string
    {
        return $this->lastname;
    }
}