<?php

declare(strict_types=1);

namespace App\Administration\Application\Command\CreateUser;

use App\Core\Application\Command\Command;

final class CreateUser implements Command
{
    public function __construct(
        private readonly string $firstname,
        private readonly string $lastname,
        private readonly string $apiKey,
        private readonly ?string $userName = null
    ) {}

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }
}