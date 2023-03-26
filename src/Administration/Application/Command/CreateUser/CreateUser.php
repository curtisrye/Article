<?php

declare(strict_types=1);

namespace App\Administration\Application\Command\CreateUser;

final class CreateUser
{
    public function __construct(
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly string $apiKey,
    ) {}

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