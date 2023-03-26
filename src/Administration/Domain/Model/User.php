<?php

declare(strict_types=1);

namespace App\Administration\Domain\Model;

final class User
{
    private function __construct(
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly string $apiKey,
    ) {}

    public static function createNewUser(string $firstname, string $lastname, string $apiKey): self
    {
        return new self(
            firstname: $firstname,
            lastname: $lastname,
            apiKey: $apiKey,
        );
    }

    public function firstname(): string
    {
        return $this->firstname;
    }

    public function lastname(): string
    {
        return $this->lastname;
    }

    public function apiKey(): string
    {
        return $this->apiKey;
    }
}