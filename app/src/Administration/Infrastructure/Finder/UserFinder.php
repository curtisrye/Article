<?php

declare(strict_types=1);

namespace App\Administration\Infrastructure\Finder;

use App\Administration\Domain\Finder\UserFinder as UserFinderInterface;
use App\Core\Domain\Model\User;
use Doctrine\DBAL\Connection;

class UserFinder implements UserFinderInterface
{
    public function __construct(private readonly Connection $connection)
    {}

    public function findByApiKey(string $apiKey): ?User
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM user 
            WHERE apiKey = :apiKey
        SQL;

        $data = $this->connection->executeQuery($sql, ['apiKey' => $apiKey])->fetchAssociative();

        if (empty($data)) {
            return null;
        }

        /** @var array<string|int> $data */
        return new User(
            id: (int) $data['id'],
            firstname: (string) $data['firstname'],
            lastname: (string) $data['lastname'],
            userName: (string) $data['userName'],
            apiKey:  (string) $data['apiKey'],
            password: (string) $data['password'],
        );
    }

    public function findByUserName(string $userName): ?User
    {
        $sql = <<< 'SQL'
            SELECT * 
            FROM user 
            WHERE userName = :userName
        SQL;

        $data = $this->connection->executeQuery($sql, ['userName' => $userName])->fetchAssociative();
        if (empty($data)) {
            return null;
        }

        /** @var array<string|int> $data */
        return new User(
            id: (int) $data['id'],
            firstname: (string) $data['firstname'],
            lastname: (string) $data['lastname'],
            userName: (string) $data['userName'],
            apiKey:  (string) $data['apiKey'],
            password: (string) $data['password'],
        );
    }
}