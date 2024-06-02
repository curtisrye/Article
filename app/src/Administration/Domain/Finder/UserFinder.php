<?php

declare(strict_types=1);

namespace App\Administration\Domain\Finder;

use App\Core\Domain\Model\User;

interface UserFinder
{
    public function findByApiKey(string $apiKey): ?User;

    public function findByUserName(string $userName): ?User;
}