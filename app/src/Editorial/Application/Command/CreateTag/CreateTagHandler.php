<?php

declare(strict_types=1);

namespace App\Editorial\Application\Command\CreateTag;

use App\Core\Application\Command\CommandHandler;
use App\Editorial\Domain\Model\Tag;
use App\Editorial\Domain\Repository\TagRepository;

class CreateTagHandler implements CommandHandler
{
    public function __construct(
        private readonly TagRepository $tagRepository,
    ) {}

    public function __invoke(CreateTag $command): void
    {
        $tag = new Tag(null, $command->name(), 0);

        $this->tagRepository->save($tag);
    }
}