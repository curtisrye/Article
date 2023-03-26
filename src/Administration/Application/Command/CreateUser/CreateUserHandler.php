<?php

declare(strict_types=1);

namespace App\Administration\Application\Command\CreateUser;

use App\Administration\Domain\Model\User;
use App\Administration\Domain\Repository\UserRepository;

class CreateUserHandler
{
    public function __construct(private readonly UserRepository $userRepository)
    {}

    public function __invoke(CreateUser $command): void
    {
        $user = User::createNewUser(
            firstname: $command->firstname(),
            lastname: $command->lastname(),
            apiKey: $command->apiKey(),
        );

        $this->userRepository->save($user);
    }
}