<?php

declare(strict_types=1);

namespace App\Administration\Presentation\Input;

final class User
{
    private string $firstName;
    private string $lastName;
    private string $userName;
    private string $password;

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}