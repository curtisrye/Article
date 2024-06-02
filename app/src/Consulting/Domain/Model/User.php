<?php

declare(strict_types=1);

namespace App\Consulting\Domain\Model;

final class User
{
    public function __construct(
        private readonly int    $id,
        private readonly string $firstname,
        private readonly string $lastname,
    ) {}

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