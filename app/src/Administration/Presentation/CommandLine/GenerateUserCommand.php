<?php

declare(strict_types=1);

namespace App\Administration\Presentation\CommandLine;

use App\Administration\Application\Command\CreateUser\CreateUser;
use App\Administration\Domain\Generator\ApiKeyGenerator;
use App\Core\Domain\Command\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    protected static $defaultDescription = 'Create a user';

    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly ApiKeyGenerator $apiKeyGenerator,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generate a user')
            ->addArgument('firstname', null, 'firstname of new user')
            ->addArgument('lastname', null, 'lastname of new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');

        $io = new SymfonyStyle($input, $output);
        $io->section(sprintf('Creation of user: %s %s', $firstname, $lastname));
        $io->newLine();

        $apiKey = $this->apiKeyGenerator->generate();

        $command = new CreateUser(
            $firstname,
            $lastname,
            $apiKey
        );
        $this->commandBus->handle($command);

        $io->section(sprintf('Creation succeed. Note your apiKey: %s', $apiKey));

        return Command::SUCCESS;
    }
}