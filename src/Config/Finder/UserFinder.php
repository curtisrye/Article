<?php

declare(strict_types=1);

namespace App\Config\Finder;

use App\Config\Model\User;
use Doctrine\DBAL\Connection;

class UserFinder
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

        $stmt = $this->connection->prepare($sql);
        $data = $stmt->executeQuery(['apiKey' => $apiKey])->fetchAssociative();

        if (empty($data)) {
            return null;
        }

        /** @var array<string|int> $data */
        return User::create(
            id: (int) $data['id'],
            firstname: (string) $data['firstname'],
            lastname: (string) $data['lastname'],
            apiKey:  (string) $data['apiKey'],
        );
    }
}