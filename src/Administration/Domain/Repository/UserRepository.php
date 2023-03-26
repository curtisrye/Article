<?php

declare(strict_types=1);

namespace App\Administration\Domain\Repository;

use App\Administration\Domain\Model\User;

interface UserRepository
{
    public function save(User $user): void;
}