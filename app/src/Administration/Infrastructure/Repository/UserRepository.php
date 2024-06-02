<?php

declare(strict_types=1);

namespace App\Administration\Infrastructure\Repository;

use App\Administration\Domain\Exception\UserNotFound;
use App\Administration\Domain\Finder\UserFinder;
use App\Administration\Domain\Repository\UserRepository as UserRepositoryInterface;
use App\Core\Domain\Model\User;
use Doctrine\DBAL\Connection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private readonly Connection $connection, private readonly UserFinder $userFinder)
    {}

    public function save(User $user): void
    {
        $this->connection->insert(
            'user',
            [
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                "userName" => $user->getUserName(),
                'apiKey' => $user->getApiKey(),
                'password' => $user->getPassword()
            ]
        );
    }

    public function getByUserName(string $userName): User
    {
        $user = $this->userFinder->findByUserName($userName);

        if ($user instanceof User) {
            return $user;
        }

        throw UserNotFound::createByUserName($userName);
    }
}