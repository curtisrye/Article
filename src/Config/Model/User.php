<?php

declare(strict_types=1);

namespace App\Config\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 */
final class User implements UserInterface
{
    private function __construct(
        /**
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        private readonly int    $id,
        /**
         * @ORM\Column(name="firstname", type="string", length=100)
         */
        private readonly string $firstname,
        /**
         * @ORM\Column(name="lastname", type="string", length=100)
         */
        private readonly string $lastname,
        /**
         * @ORM\Column(name="apiKey", type="string", length=255)
         */
        private readonly string $apiKey,
    ) {}

    public static function create(int $id, string $firstname, string $lastname, string $apiKey): self
    {
        return new self(
            id: $id,
            firstname: $firstname,
            lastname: $lastname,
            apiKey: $apiKey,
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

    public function apiKey(): string
    {
        return $this->apiKey;
    }

    public function getRoles(): array
    {
        return [];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->apiKey;
    }
}