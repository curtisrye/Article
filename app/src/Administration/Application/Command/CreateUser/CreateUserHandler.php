<?php

declare(strict_types=1);

namespace App\Administration\Application\Command\CreateUser;

use App\Administration\Domain\Repository\UserRepository;
use App\Core\Application\Command\CommandHandler;
use App\Core\Domain\Model\User;

class CreateUserHandler implements CommandHandler
{
    public function __construct(private readonly UserRepository $userRepository)
    {}

    public function __invoke(CreateUser $command): void
    {
        $user = User::create(
            firstname: $command->getFirstname(),
            lastname: $command->getLastname(),
            userName: $command->getUserName() ?? $command->getFirstname() . '-' . $command->getLastname(),
            apiKey: $command->getApiKey(),
            password: null
        );

        $this->userRepository->save($user);
    }
}