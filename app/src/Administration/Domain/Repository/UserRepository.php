<?php

declare(strict_types=1);

namespace App\Administration\Domain\Repository;

use App\Core\Domain\Model\User;

interface UserRepository
{
    public function save(User $user): void;

    public function getByUserName(string $userName): User;
}