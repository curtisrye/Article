<?php

declare(strict_types=1);

namespace App\Editorial\Infrastructure\Repository;

use App\Editorial\Domain\Exception\UserNotFound;
use App\Editorial\Domain\Model\User;
use App\Editorial\Domain\Repository\UserRepository as UserRepositoryInterface;
use Doctrine\DBAL\Connection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly Connection $connection)
    {}

    public function get(int $userId): User
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM user 
            WHERE id = :userId
        SQL;

        $data = $this->connection->executeQuery($sql, ['userId' => $userId])->fetchAssociative();
        if (empty($data)) {
            throw UserNotFound::create($userId);
        }

        /** @var array<string|int> $data */
        return User::create(
            id: (int) $data['id'],
            firstname: (string) $data['firstname'],
            lastname: (string) $data['lastname'],
        );
    }
}