<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[UniqueEntity(fields: ['apiKey'], message: 'There is already an account with this apiKey')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(

        #[ORM\Column(name: "id", type: "integer")]
        #[ORM\Id]
        #[ORM\GeneratedValue(strategy: "AUTO")]
        private readonly ?int $id,

        #[ORM\Column(name: "firstname", type: "string", length: 100)]
        private readonly string $firstname,

        #[ORM\Column(name: "lastname", type: "string", length: 100)]
        private readonly string $lastname,

        #[ORM\Column(name: "userName", type: "string", length: 100)]
        private readonly string $userName,

        #[ORM\Column(name: "apiKey", type: "string", length: 255)]
        private readonly string $apiKey,

        #[ORM\Column(name: "password", type: "string", length: 255)]
        private ?string $password,
    ) {}

    public static function create(string $firstname, string $lastname, string $userName, string $apiKey, ?string $password): self
    {
        return new self(
            null,
            firstname: $firstname,
            lastname: $lastname,
            userName: $userName,
            apiKey: $apiKey,
            password: $password,
        );
    }

    public function getId(): int
    {
        if (null === $this->id) {
            throw new \LogicException('Attempt to acces on empty user.');
        }

        return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->apiKey;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}