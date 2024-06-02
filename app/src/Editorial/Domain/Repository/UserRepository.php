<?php

declare(strict_types=1);

namespace App\Editorial\Domain\Repository;

use App\Editorial\Domain\Model\User;

interface UserRepository
{
    public function get(int $userId): User;
}