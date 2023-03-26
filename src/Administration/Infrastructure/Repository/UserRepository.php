<?php

declare(strict_types=1);

namespace App\Administration\Infrastructure\Repository;

use App\Administration\Domain\Model\User;
use App\Administration\Domain\Repository\UserRepository as UserRepositoryInterface;
use Doctrine\DBAL\Connection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {}

    public function save(User $user): void
    {
        $this->connection->insert(
            'user',
            [
                'id' => null,
                'firstname' => $user->firstname(),
                'lastname' => $user->lastname(),
                'apiKey' => $user->apiKey(),
            ]
        );
    }
}